<?php

namespace App\Http\Controllers\HotelRoom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRoom\HotelRoomTypeCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomTypeUpdateRequest;
use App\Repositories\HotelRoom\HotelRoomTypeRepositoryInterface;

class HotelRoomTypeController extends Controller
{
    protected $hotelRoomTypeInterface;

    public function __construct(HotelRoomTypeRepositoryInterface $hotelRoomTypeInterface)
    {
        $this->hotelRoomTypeInterface = $hotelRoomTypeInterface;
    }
    
    public function index(Request $request)
    {
        return $this->hotelRoomTypeInterface->index($request);
    }

    public function show($id)
    {
        return $this->hotelRoomTypeInterface->show($id);
    }

    public function store(HotelRoomTypeCreateRequest $request)
    {
        return $this->hotelRoomTypeInterface->store($request);
    }

    public function update(HotelRoomTypeUpdateRequest $request, $id)
    {
        return $this->hotelRoomTypeInterface->update($request, $id);
    }

    public function delete($id)
    {
        return $this->hotelRoomTypeInterface->delete($id);
    }
}
