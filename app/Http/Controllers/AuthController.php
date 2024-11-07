<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\AuthValidate;
use App\Http\Requests\LoginValidate;
use App\Http\Requests\VerificationValidate;
use App\Services\AuthService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    protected AuthService $auth;
    public function __construct(AuthService $authService)
    {
        $this->auth = $authService;
    }

    public function register(AuthValidate $request)
    {
        try {
            $userDTO = UserDTO::fromArray($request->validated());
            $token = $this->auth->register($userDTO);
            return $this->successResponse($token, 'Code sent to your email', 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function verification(VerificationValidate $request)
    {
        try {
            $result = $this->auth->verification($request);

            if($result) {
                return $this->successResponse([], 'Your account has been confirmed', 200);

            } else {
                return $this->badRequestResponse('your code is not correct');
            }

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function login(LoginValidate $request)
    {
        try {
            $loginDTO = UserDTO::fromArray($request->validated());
            $token = $this->auth->login($loginDTO);

            if ($token) {
                return $this->successResponse($token, 'Login successful');

            } else {
                return $this->errorResponse('Unauthorized',401);
            }

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }

    public function logout()
    {
        try {
            $this->auth->logout();
            return $this->successResponse([], 'Logout successful');

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
