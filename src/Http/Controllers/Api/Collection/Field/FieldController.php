<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection\Field;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\CreateFieldRequest;
use Sakydev\Boring\Resources\Api\FieldResource;
use Sakydev\Boring\Resources\Api\Responses\BadRequestErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\FieldService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FieldController
{
    public function __construct(readonly FieldService $fieldService) {}

    public function store(CreateFieldRequest $createRequest, string $collectionName): JsonResponse {
        try {
            $field = $this->fieldService->store($createRequest->validated(), $collectionName);

            return new SuccessResponse('item.success.createOne', [
                'field' => new FieldResource($field),
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Create field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
