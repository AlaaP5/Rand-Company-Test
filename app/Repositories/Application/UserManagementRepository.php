<?php

namespace App\Repositories\Application;

use App\DTOs\UserDTO;
use App\Enums\AuthCases;
use App\Events\CreateUserEvent;
use App\Helpers\DateNow;
use App\Interfaces\Application\IUserManagementRepository;
use App\Interfaces\Domain\IUserRepository;
use App\Mail\SendCodeMail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserManagementRepository implements IUserManagementRepository
{
    public function __construct(protected IUserRepository $UserRepository) {}


    private function hash_password($password)
    {
        $hash_password = Hash::make($password);
        return $hash_password;
    }

    private function verify_password($password, $hashed_password)
    {
        return Hash::check($password, $hashed_password);
    }

    private function random_number()
    {
        return random_int(1000, 9999);
    }

    private function verify_for_login($input, $db)
    {
        if($this->verify_password($input[AuthCases::Password->value], $db->password)  && $db->email === $input[AuthCases::Email->value]) {
            return true;
        }

        return false;
    }


    public function register(UserDTO $userDTO)
    {
        $input = $userDTO->toArray();

        $input[AuthCases::Password->value] = $this->hash_password($userDTO->password);

        $input[AuthCases::Role->value] = AuthCases::User->value;

        $input[AuthCases::Date->value] = DateNow::presentTime(now());

        $user = $this->UserRepository->create_user($input);

        $data[AuthCases::Token->value] = $user->createToken(AuthCases::Having->value)->accessToken;

        Event::dispatch(new CreateUserEvent($user));

        return $data;
    }


    public function verification($request)
    {
        $user = $this->UserRepository->get_user_by_id();

        if ($request->code == $user->code) {

            $this->UserRepository->update_user_by_email($user->email, null, true);
            return true;

        } else
            return false;
    }


    public function login(UserDTO $userDTO)
    {
        $input = $userDTO->toArray();

        $user = $this->UserRepository->get_user_by_email($userDTO->email);

        if(!empty($user)) {

            if($this->verify_for_login($input, $user)) {

                if($user->statusCode != true) {
                    return null;
                }

                $data[AuthCases::Token->value] = $user->createToken(AuthCases::Having->value)->accessToken;

                return $data;
            }
            return false;
        }

        return false;

    }


    public function logout()
    {
        return $this->UserRepository->logout();
    }


    public function sendCode($data)
    {
        $code = $this->random_number();
        $user = $this->UserRepository->update_user_by_email($data->email, $code, false);

        Mail::to($user->email)->send(new SendCodeMail($user));
    }
}
