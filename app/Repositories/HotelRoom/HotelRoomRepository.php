<?php

namespace App\Repositories\HotelRoom;

use App\Models\HotelRoom;
use Illuminate\Http\Request;
use App\Http\Requests\HotelRoom\HotelRoomCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomUpdateRequest;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;
use Illuminate\Support\Facades\DB;

class HotelRoomRepository implements HotelRoomRepositoryInterface
{
    public function index(Request $request)
    {
        $data = HotelRoom::when($request->search, function ($query, $search) {
            return $query->where('room_number', 'like', "%{$search}%");
        })->orderBy('room_number')->get();

        return Response()->json(['data' => $data]);
    }

    public function show($id)
    {
        if (!$data = HotelRoom::find($id)) {
            return Response()->json(['message' => 'Hotel room not found'], 404);
        }

        return Response()->json(['data' => $data]);
    }

    public function store(HotelRoomCreateRequest $request)
    {
        $data = HotelRoom::create($request->all());

        return Response()->json([
            'message' => 'Hotel room created successfully',
            'data' => $data
        ]);
    }

    public function update(HotelRoomUpdateRequest $request, $id)
    {
        if (!$hotelRoom = HotelRoom::findOrFail($id)) {
            return Response()->json(['message' => 'Hotel room not found'], 404);
        }

        $hotelRoom->update($request->all());
        $hotelRoom->refresh();

        return Response()->json([
            'message' => 'Hotel room updated successfully',
            'data' => $hotelRoom
        ]);
    }

    public function delete($id)
    {
        if (!$hotelRoom = HotelRoom::findOrFail($id)) {
            return Response()->json(['message' => 'Hotel room not found'], 404);
        }

        $hotelRoom->delete();

        return Response()->json([
            'message' => 'Hotel room deleted successfully',
            'data' => $hotelRoom
        ]);
    }

    public function vacantRoom(Request $request)
    {
        $request->validate([
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date',
            'room_type_id' => 'required|exists:hotel_room_types,id'
        ]);

        if ($request->checkin_date > $request->checkout_date) {
            return Response()->json(['message' => 'Checkin date must be less than checkout date'], 422);
        }

        $checkinDate = date('Y-m-d', strtotime($request->checkin_date));
        $checkoutDate = date('Y-m-d', strtotime($request->checkout_date));

        $availableRooms = HotelRoom::select('hotel_rooms.id', 'hotel_room_types.id AS type_id', 'hotel_room_types.room_type', 'hotel_rooms.room_number')
            ->leftJoin('hotel_room_types', 'hotel_rooms.room_type_id', '=', 'hotel_room_types.id')
            ->leftJoin('booking_order_details', function($join) use($checkinDate, $checkoutDate) {
                $join->on('booking_order_details.room_id', '=', 'hotel_rooms.id')
                     ->whereBetween('booking_order_details.access_date', [$checkinDate, $checkoutDate]);
            })
            ->whereNull('booking_order_details.access_date')
            ->where('hotel_room_types.id', (int)$request->room_type_id)
            ->get();

        if ($availableRooms->isEmpty()) {
            return Response()->json(['message' => 'No vacant room found for that room type'], 404);
        }

        return Response()->json(['data' => $availableRooms->makeHidden('room_type')]);
    }
}
