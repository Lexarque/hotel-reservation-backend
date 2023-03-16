<?php

namespace App\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function index(Request $request)
    {
        return $this->userRepositoryInterface->index($request);
    }

    public function show($id)
    {
        return $this->userRepositoryInterface->show($id);
    }

    public function store(Request $request)
    {
        return $this->userRepositoryInterface->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->userRepositoryInterface->update($request, $id);
    }

    public function delete($id)
    {
        return $this->userRepositoryInterface->delete($id);
    }
}