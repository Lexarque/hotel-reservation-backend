<?php

namespace App\Repositories\HotelRoom;

use Illuminate\Http\Request;
use App\Http\Requests\HotelRoom\HotelRoomCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomUpdateRequest;

interface HotelRoomRepositoryInterface
{
    public function index(Request $request);

    public function store(HotelRoomCreateRequest $request);

    public function show($id);

    public function update(HotelRoomUpdateRequest $request, $id);

    public function delete($id);

    public function vacantRoom(Request $request);
}
