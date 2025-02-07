<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Enums\AuthCases;
use App\Http\Requests\AuthValidate;
use App\Http\Requests\LoginValidate;
use App\Http\Requests\VerificationValidate;
use App\Interfaces\Application\IUserManagementRepository;
use App\Traits\ApiResponse;


class AuthController extends Controller
{
    use ApiResponse;
    public function __construct(protected IUserManagementRepository $UserManagementRepository) {}

    public function register(AuthValidate $request)
    {
        try {
            $userDTO = UserDTO::fromArray($request->validated());

            $token = $this->UserManagementRepository->register($userDTO);

            return $this->successResponse($token, AuthCases::Register_success->value, 201);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function verification(VerificationValidate $request)
    {
        try {
            $result = $this->UserManagementRepository->verification($request);

            if($result) {
                return $this->successResponse([], AuthCases::verification_success->value, 200);

            } else {
                return $this->badRequestResponse(AuthCases::verification_failed->value);
            }

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }


    public function login(LoginValidate $request)
    {
        try {
            $loginDTO = UserDTO::fromArray($request->validated());
            $token = $this->UserManagementRepository->login($loginDTO);

            if ($token) {
                return $this->successResponse($token, AuthCases::Login_success->value);

            } else if(is_null($token)) {
                return $this->forbiddenResponse(AuthCases::Forbidden_message->value);

            } else {
                return $this->errorResponse(AuthCases::Failed->value, 401);
            }

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }


    public function logout()
    {
        try {
            $this->UserManagementRepository->logout();
            return $this->successResponse([], AuthCases::Logout_success->value);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
