<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Events\CreateUserEvent;
use App\Helpers\DateNow;
use App\Interfaces\AuthRepositoryInterface;
use App\Mail\SendCodeMail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function __construct(protected AuthRepositoryInterface $authRepository) {}


    private function hash_password($password) {

        $hash_password = Hash::make($password);
        return $hash_password;
    }

    private function verify_password($password, $hashed_password) {
        return Hash::check($password, $hashed_password);
    }

    private function random_number() {
        return random_int(1000, 9999);
    }

    private function verify_for_login($input, $db) {

        if($this->verify_password($input['password'], $db->password)  && $db->email === $input['email']) {
            return true;
        }

        return false;
    }


    public function register(UserDTO $userDTO)
    {
        $input = $userDTO->toArray();

        $input['password'] = $this->hash_password($userDTO->password);

        $input['role'] = 'user';

        $input['date'] = DateNow::presentTime(now());

        $user = $this->authRepository->create_user($input);

        $data['token'] = $user->createToken('Having')->accessToken;

        Event::dispatch(new CreateUserEvent($user));

        return $data;
    }


    public function verification($request)
    {
        $user = $this->authRepository->get_user_by_id();

        if ($request->code == $user->code) {

            $this->authRepository->update_user_by_email($user->email, null, true);
            return true;

        } else
            return false;
    }


    public function login(UserDTO $userDTO)
    {
        $input = $userDTO->toArray();

        $user = $this->authRepository->get_user_by_email($userDTO->email);

        if($this->verify_for_login($input, $user)) {

            if($user->statusCode != true) {
                return null;
            }

            $data['token'] = $user->createToken('Having')->accessToken;

            return $data;
        }
        return false;
    }


    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function sendCode($data)
    {
        $code = $this->random_number();
        $user = $this->authRepository->update_user_by_email($data->email, $code, false);

        Mail::to($user->email)->send(new SendCodeMail($user));
    }
}
