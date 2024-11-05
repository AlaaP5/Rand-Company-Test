<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Interfaces\AuthRepositoryInterface;


class AuthService
{
    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository) {
        $this->authRepository = $authRepository;
    }


    public function register(UserDTO $userDTO)
    {
        return $this->authRepository->register($userDTO->toArray());
    }

    public function verification($request)
    {
        return $this->authRepository->verification($request);
    }

    public function login(UserDTO $userDTO)
    {
        return $this->authRepository->login($userDTO->toArray());
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function sendCode($request)
    {
        return $this->authRepository->sendCode($request);
    }
}
