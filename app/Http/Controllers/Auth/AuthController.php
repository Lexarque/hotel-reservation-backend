<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Auth\AuthRepositoryInterface;

class AuthController extends Controller
{   
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        return $this->authRepository->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authRepository->logout($request);
    }

    public function me(Request $request) {
        return $this->authRepository->me($request);
    }

    public function register(Request $request) {
        return $this->authRepository->register($request);
    }
}
