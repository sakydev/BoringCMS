<?php

namespace Sakydev\Boring\Http\Controllers\Api\Field;

use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\Field\CreateFieldRequest;
use Sakydev\Boring\Resources\Api\FieldResource;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\FieldService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FieldController
{
    public function __construct(readonly FieldService $fieldService) {}

    public function store(CreateFieldRequest $createRequest, int $collectionId): JsonResponse {
        try {
            $form = $this->fieldService->store($createRequest->validated(), $collectionId);

            return new SuccessResponse('item.success.createOne', [
                'field' => new FieldResource($form),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            Log::error('Create form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
