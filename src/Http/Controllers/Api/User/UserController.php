<?php

namespace Sakydev\Boring\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Repositories\BoringUserRepository;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    public function __construct(readonly BoringUserRepository $userRepository) {}

    public function me(): SuccessResponse|ErrorResponse {
        try {
            $user = $this->userRepository->getById(Auth::id());

            return new SuccessResponse('item.success.findOne', [
                'user' => new BoringUserResource($user),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('User show failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
