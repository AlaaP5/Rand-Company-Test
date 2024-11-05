<?php

namespace App\Repositories;

use App\Events\CreateUserEvent;
use App\Helpers\DateNow;
use App\Interfaces\AuthRepositoryInterface;
use App\Mail\SendCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $input)
    {
        $input['password'] = Hash::make($input['password']);
        $input['role'] = 'user';
        $input['date'] = DateNow::presentTime(now());
        $user = User::create($input);
        $token = $user->createToken('Having')->accessToken;

        Event::dispatch(new CreateUserEvent($user));
        return $token;
    }


    public function sendCode($request)
    {
            $code = random_int(1000, 9999);
            $user = User::where('email', $request->email)->first();
            $user->code = $code;
            $user->statusCode = false;
            $user->save();

            Mail::to($user->email)->send(new SendCodeMail($user));
    }


    public function verification($request)
    {
        $user = User::findOrFail(Auth::id());

        if ($request->code == $user->code) {
            $user->statusCode = true;
            $user->save();
            return true;
        } else
            return false;
    }


    public function login(array $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = auth()->user();

            if ($user->statusCode != true) {
                throw new \Exception('user account inactive');
            }

            $token = $user->createToken('Having')->accessToken;
            return $token;
        }
        return false;
    }


    public function logout()
    {
        /**@var \App\Models\MyUserModel */
        $user = auth()->user();
        $user->tokens()->delete();
        return true;
    }
}
