<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthRepository implements AuthRepositoryInterface
{

    public function create_user(array $input)
    {
        $user = User::create($input);

        return $user;
    }


    public function get_user_by_id()
    {
        $user = User::findOrFail(Auth::id());

        return $user;
    }


    public function update_user_by_email($email, $code, $status)
    {
            $user = User::where('email', $email)->first();

            $user->code = is_null($code) ? $user->code : $code;
            $user->statusCode = $status;
            $user->save();

            return $user;
    }





    public function get_user_by_email($email)
    {
        $user = User::where('email', $email)->first();

        return $user;
    }


    public function logout()
    {
        /**@var \App\Models\MyUserModel */
        $user = auth()->user();
        $user->tokens()->delete();
        return true;
    }
}
