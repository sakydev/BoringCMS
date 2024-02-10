<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\LoginUserRequest;
use Sakydev\Boring\Repositories\BoringUserRepository;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    public function __construct(readonly BoringUserRepository $userRepository) {}

    public function login(LoginUserRequest $loginRequest): SuccessResponse|ErrorResponse {
        try {
            if (Auth::attempt($loginRequest->only(['email', 'password']))) {

                $token = auth()->user()->createToken('auth_token')->plainTextToken;
                $user = $this->userRepository->getByEmail($loginRequest->email);
                $user->auth_token = $token;

                return new SuccessResponse('auth.success.login', [
                    'user' => new BoringUserResource($user),
                ], Response::HTTP_OK);
            }


            return new ErrorResponse(
                'auth.error.invalidCredentials',
                Response::HTTP_UNAUTHORIZED
            );
        } catch (Throwable $throwable) {
            Log::error('User login failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
