<?php

namespace App\Interfaces\Application;

use App\DTOs\UserDTO;

interface IUserManagementRepository
{

    public function register(UserDTO $userDTO);
    public function verification($data);
    public function login(UserDTO $userDTO);
    public function logout();
    public function sendCode($data);
}
