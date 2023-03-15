<?php

namespace App\Http\Controllers\HotelRoom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRoom\HotelRoomCreateRequest;
use App\Http\Requests\HotelRoom\HotelRoomUpdateRequest;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;

class HotelRoomController extends Controller
{
    protected $hotelRoomRepository;

    public function __construct(HotelRoomRepositoryInterface $hotelRoomRepository)
    {
        $this->hotelRoomRepository = $hotelRoomRepository;
    }
    
    public function index(Request $request)
    {
        return $this->hotelRoomRepository->index($request);
    }

    public function show($id)
    {
        return $this->hotelRoomRepository->show($id);
    }

    public function store(HotelRoomCreateRequest $request)
    {
        return $this->hotelRoomRepository->store($request);
    }

    public function update(HotelRoomUpdateRequest $request, $id)
    {
        return $this->hotelRoomRepository->update($request, $id);
    }

    public function delete($id)
    {
        return $this->hotelRoomRepository->delete($id);
    }

    public function vacantRoom(Request $request)
    {
        return $this->hotelRoomRepository->vacantRoom($request);
    }
}
