<?php

namespace App\Repositories\BookingOrder;

use Carbon\Carbon;
use App\Models\HotelRoom;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;
use App\Http\Requests\BookingOrder\BookingOrderCreateRequest;
use App\Http\Requests\BookingOrder\BookingOrderUpdateRequest;
use App\Repositories\BookingOrder\BookingOrderRepositoryInterface;
use Termwind\Components\Dd;

class BookingOrderRepository implements BookingOrderRepositoryInterface
{
    protected $hotelRoomRepository;

    public function __construct(HotelRoomRepositoryInterface $hotelRoomRepository)
    {
        $this->hotelRoomRepository = $hotelRoomRepository;
    }

    public function index(Request $request)
    {
        $data = BookingOrder::when($request->search, function ($query, $search) {
            return $query->where('room_number', 'like', "%{$search}%");
        })
            ->when($request->checkin_date || $request->checkout_date, function ($query) use ($request) {
                return $query->where(function ($subquery) use ($request) {
                    return $subquery->whereBetween('checkin_date', [$request->checkin_date ?? now(), $request->checkout_date ?? now()])
                        ->whereBetween('checkout_date', [$request->checkin_date ?? now(), $request->checkout_date ?? now()]);
                });
            })->get();

        return Response()->json(['data' => $data]);
    }

    public function show($id)
    {
        if (!$data = BookingOrder::find($id)) {
            return Response()->json(['message' => 'Booking order data not found'], 404);
        }

        $user = auth()->guard('api')->user();
        if (!in_array($user->role, ['superadmin', 'admin', 'receptionist']) && $user->id != $data->user_id) {
            return Response()->json(['message' => 'You are not authorized to access this data'], 403);
        }

        return Response()->json(['data' => $data]);
    }

    public function store(BookingOrderCreateRequest $request)
    {
        $request->merge([
            'room_count' => count($request->room_ids),
            'user_id' => auth()->guard('api')->user()->id
        ]);

        if ($request->checkin_date > $request->checkout_date) {
            return Response()->json(['message' => 'Checkin date must be less than checkout date'], 422);
        }

        if ($request->checkin_date < now()->format('Y-m-d')) {
            return Response()->json(['message' => 'Checkin date must be greater than today'], 422);
        }

        // Call vacant room method from the hotel room repository
        $vacantRooms = $this->hotelRoomRepository->vacantRoom($request);
        $vacantRooms = json_decode($vacantRooms->getContent(), true);
        if (!isset($vacantRooms['data'])) {
            return $vacantRooms;
        }
        
        // Check if the room IDs are already booked
        foreach ($request->room_ids as $room_id) {
            if (!in_array((int)$room_id, array_column($vacantRooms['data'], 'id'))) {
                return response()->json(['message' => 'Room number ' . $room_id . ' has already been booked'], 422);
            }
        }

        // Create the booking order
        $bookingOrder = BookingOrder::create($request->all());

        // Calculate the staying period
        $checkinDate = Carbon::parse($request->checkin_date)->startOfDay();
        $checkoutDate = Carbon::parse($request->checkout_date)->startOfDay();
        $stayingPeriod = $checkinDate->diffInDays($checkoutDate);

        // Loop through each room ID
        foreach ($request->room_ids as $room_id) {
            $hotelRoom = HotelRoom::where('id', $room_id)->first();
            $currentDate = $checkinDate->copy();
            for ($i = 0; $i < $stayingPeriod; $i++) {
                $bookingOrder->booking_order_details()->create([
                    'staying_period' => $stayingPeriod,
                    'price' => $hotelRoom->room_type->price,
                    'booking_order_id' => $bookingOrder->id,
                    'room_id' => $hotelRoom->id,
                    'access_date' => $currentDate->format('Y-m-d'),
                ]);
                // Increment the current date
                $currentDate->addDay();
            }
        }

        return Response()->json([
            'message' => 'Booking Order created successfully',
            'data' => $bookingOrder
        ]);
    }

    public function update(BookingOrderUpdateRequest $request, $id)
    {
        if (!$bookingOrder = BookingOrder::findOrFail($id)) {
            return Response()->json(['message' => 'Booking order data not found'], 404);
        }

        $request->merge([
            'room_count' => count($request->room_ids ?? []),
            'user_id' => auth()->guard('api')->user()->id
        ]);

        if (@$request->checkin_date > @$request->checkout_date) {
            return Response()->json(['message' => 'Checkin date must be less than checkout date'], 422);
        }

        if ($request->room_count == 0) {
            $bookingOrder->booking_order_details()->delete();
        } else {
            // Filter the room_id that doesn't exist in the database
            $existingRoomIds = $bookingOrder->booking_order_details()
                ->groupBy('room_id')
                ->pluck('room_id')
                ->toArray();
            $newRooms = array_filter($request->room_ids, function ($room_id) use ($existingRoomIds) {
                return !in_array($room_id, $existingRoomIds);
            });

            // If no new rooms is present, just update the existing rooms
            if ($newRooms == []) {
                $newRooms = array_filter($existingRoomIds, function ($room_id) use ($request) {
                    return in_array($room_id, $request->room_ids);
                });
            } else {
                // Call vacant room method from the hotel room repository
                $vacantRooms = $this->hotelRoomRepository->vacantRoom($request);
                if (!isset($vacantRooms['data'])) {
                    return $vacantRooms;
                }

                // Check if the room IDs are already booked
                foreach ($request->room_ids as $room_id) {
                    if (in_array($room_id, $vacantRooms['data'])) {
                        return Response()->json(['message' => 'Room ID ' . $room_id . ' is already booked'], 422);
                    }
                }
            }

            // Delete the detail with room_id that doesn't exist in the request
            $bookingOrder->booking_order_details()->whereNotIn('room_id', $request->room_ids)->delete();

            // Calculate the staying period
            $checkinDate = Carbon::parse($request->checkin_date ?? $bookingOrder->checkin_date)->startOfDay();
            $checkoutDate = Carbon::parse($request->checkout_date ?? $bookingOrder->checkout_date)->startOfDay();
            $stayingPeriod = $checkinDate->diffInDays($checkoutDate);

            // Loop through each room ID
            foreach ($newRooms as $room_id) {
                $hotelRoom = HotelRoom::where('id', $room_id)->first();
                $currentDate = $checkinDate->copy();
                for ($i = 0; $i < $stayingPeriod; $i++) {
                    $bookingOrder->booking_order_details()->updateOrCreate([
                        'staying_period' => $stayingPeriod,
                        'price' => $hotelRoom->room_type->price,
                        'booking_order_id' => $bookingOrder->id,
                        'room_id' => $hotelRoom->id,
                        'access_date' => $currentDate->format('Y-m-d'),
                    ], [
                        'room_id' => $hotelRoom->id,
                    ]);
                    // Increment the current date
                    $currentDate->addDay();
                }
            }
        }

        $bookingOrder->update($request->all());
        $bookingOrder->refresh();

        return Response()->json([
            'message' => 'Booking Order updated successfully',
            'data' => $bookingOrder
        ]);
    }

    public function delete($id)
    {
        if (!$bookingOrder = BookingOrder::findOrFail($id)) {
            return Response()->json(['message' => 'Booking Order not found'], 404);
        }

        $bookingOrder->delete();

        return Response()->json([
            'message' => 'Booking Order deleted successfully',
            'data' => $bookingOrder
        ]);
    }
}
