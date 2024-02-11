<?php

namespace Sakydev\Boring\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Resources\Api\BoringUserResource;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\NotFoundErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    public function __construct(readonly UserService $userService) {}

    public function me(): JsonResponse {
        try {
            $user = $this->userService->getById(Auth::id());

            return new SuccessResponse('item.success.findOne', [
                'user' => new BoringUserResource($user),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('User show failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
