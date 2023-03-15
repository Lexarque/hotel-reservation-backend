<?php

namespace App\Repositories\HotelRoom;

use App\Http\Requests\HotelRoom\HotelRoomTypeCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomTypeUpdateRequest;
use Illuminate\Http\Request;

interface HotelRoomTypeRepositoryInterface {
    public function index(Request $request);

    public function store(HotelRoomTypeCreateRequest $request);

    public function show($id);

    public function update(HotelRoomTypeUpdateRequest $request, $id);

    public function delete($id);
}