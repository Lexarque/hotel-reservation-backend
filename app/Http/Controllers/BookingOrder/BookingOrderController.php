<?php

namespace App\Http\Controllers\BookingOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingOrder\BookingOrderCreateRequest;
use App\Http\Requests\BookingOrder\BookingOrderUpdateRequest;
use App\Repositories\BookingOrder\BookingOrderRepositoryInterface;

class BookingOrderController extends Controller
{
    protected $bookingOrderRepository;

    public function __construct(BookingOrderRepositoryInterface $bookingOrderRepository)
    {
        $this->bookingOrderRepository = $bookingOrderRepository;
    }

    public function index(Request $request)
    {
        return $this->bookingOrderRepository->index($request);
    }

    public function show($id)
    {
        return $this->bookingOrderRepository->show($id);
    }

    public function store(BookingOrderCreateRequest $request)
    {
        return $this->bookingOrderRepository->store($request);
    }

    public function update(BookingOrderUpdateRequest $request, $id)
    {
        return $this->bookingOrderRepository->update($request, $id);
    }

    public function delete($id)
    {
        return $this->bookingOrderRepository->delete($id);
    }
}
