<?php

namespace App\Interfaces;


interface AuthRepositoryInterface
{
    public function create_user(array $request);
    public function get_user_by_id();
    public function get_user_by_email($email);
    public function update_user_by_email($email, $code, $status);
    public function logout();
}
