<?php

namespace Sakydev\Boring\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\User\RegisterUserRequest;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(readonly UserService $userService) {}

    public function store(RegisterUserRequest $createRequest): SuccessResponse|ErrorResponse {
        try {
            $user = $this->userService->store($createRequest->validated());

            return new SuccessResponse('auth.success.register', [
                'user' => new BoringUserResource($user),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            Log::error('Create user failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
