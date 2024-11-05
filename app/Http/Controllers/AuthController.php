<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\AuthValidate;
use App\Http\Requests\LoginValidate;
use App\Http\Requests\VerificationValidate;
use App\Services\AuthService;


class AuthController extends Controller
{
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
            return response()->json(['token' => $token, 'message' => 'Code sent to your email'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function verification(VerificationValidate $request)
    {
        try {
            $result = $this->auth->verification($request);

            if($result) {
                return response()->json(['message' => 'Your account has been confirmed'], 200);
            } else {
                return response()->json(['message' => 'your code is not correct'], 422);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function login(LoginValidate $request)
    {
        try {
            $loginDTO = UserDTO::fromArray($request->validated());
            $token = $this->auth->login($loginDTO);
            if ($token) {
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['message' => 'Invalid login'], 422);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $this->auth->logout();
            return response()->json(['message' => 'logged out Successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
