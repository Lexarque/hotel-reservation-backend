<?php

namespace App\Repositories\BookingOrder;

use App\Http\Requests\BookingOrder\BookingOrderCreateRequest;
use App\Http\Requests\BookingOrder\BookingOrderUpdateRequest;
use Illuminate\Http\Request;

interface BookingOrderRepositoryInterface
{
    public function index(Request $request);

    public function store(BookingOrderCreateRequest $request);

    public function show($id);

    public function update(BookingOrderUpdateRequest $request, $id);

    public function delete($id);
}
