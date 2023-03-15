<?php

namespace App\Repositories\HotelRoom;

use Illuminate\Http\Request;
use App\Models\HotelRoomType;
use App\Http\Requests\HotelRoom\HotelRoomTypeCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomTypeUpdateRequest;

class HotelRoomTypeRepository implements HotelRoomTypeRepositoryInterface {
    
    public function index(Request $request)
    {
        $data = HotelRoomType::when($request->search, function ($query, $search) {
            return $query->where('room_type', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->get();

        return Response()->json(['data' => $data]);
    }

    public function show($id)
    {
        return Response()->json(['data' => HotelRoomType::findOrFail($id)]);
    }

    public function store(HotelRoomTypeCreateRequest $request)
    {
        $data = HotelRoomType::create($request->all());

        return Response()->json([
            'message' => 'Hotel room type created successfully',
            'data' => $data
        ]);
    }

    public function update(HotelRoomTypeUpdateRequest $request, $id) 
    {
        $hotelRoom = HotelRoomType::findOrFail($id);
        $hotelRoom->update($request->all());
        $hotelRoom->refresh();

        return Response()->json([
            'message' => 'Hotel room type updated successfully',
            'data' => $hotelRoom
        ]);
    }

    public function delete($id)
    {
        $hotelRoom = HotelRoomType::findOrFail($id);
        $hotelRoom->delete();

        return Response()->json([
            'message' => 'Hotel room type deleted successfully',
            'data' => $hotelRoom
        ]);
    }
}