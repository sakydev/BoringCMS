<?php

namespace Sakydev\Boring\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\UnauthorisedException;
use Sakydev\Boring\Http\Requests\Api\User\LoginUserRequest;
use Sakydev\Boring\Repositories\BoringUserRepository;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    public function __construct(readonly UserService $userService) {}

    public function login(LoginUserRequest $loginRequest): SuccessResponse|ErrorResponse {
        try {
            $user = $this->userService->login($loginRequest->only(['email', 'password']));

            return new SuccessResponse('auth.success.login', [
                'user' => new BoringUserResource($user),
            ], Response::HTTP_OK);
        } catch (UnauthorisedException $exception) {
            return new ErrorResponse($exception->getMessage(), $exception->getCode());
        } catch (Throwable $throwable) {
            Log::error('User login failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
