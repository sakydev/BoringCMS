<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\RegisterUserRequest;
use Sakydev\Boring\Repositories\BoringUserRepository;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(readonly BoringUserRepository $userRepository) {}

    public function store(RegisterUserRequest $createRequest): SuccessResponse|ErrorResponse {
        try {
            $requestContent = $createRequest->validated();
            $requestContent['password'] = Hash::make($createRequest->password);

            $user = $this->userRepository->store($requestContent);

            return new SuccessResponse('users.success.store.single', [
                'user' => new BoringUserResource($user),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            Log::error('Create user failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('users.failed.store.unknown');
        }
    }
}
