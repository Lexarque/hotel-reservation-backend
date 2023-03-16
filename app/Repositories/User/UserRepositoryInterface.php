<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index(Request $request);

    public function show($id);

    public function store(Request $request);

    public function update(Request $request, $id);

    public function delete($id);
}