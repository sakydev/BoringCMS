<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\CreateFieldRequest;
use Sakydev\Boring\Resources\Api\CollectionResource;
use Sakydev\Boring\Resources\Api\Responses\BadRequestErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\CollectionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CollectionController
{
    public function __construct(readonly CollectionService $collectionService) {}

    public function store(CreateFieldRequest $createRequest, string $collectionName): JsonResponse {
        try {
            $field = $this->collectionService->store($createRequest->validated(), $collectionName);

            return new SuccessResponse('item.success.createOne', [
                'field' => new CollectionResource($field),
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Create field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
