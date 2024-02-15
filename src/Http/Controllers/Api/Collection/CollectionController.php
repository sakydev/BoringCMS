<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Http\Requests\Api\Collection\CreateCollectionRequest;
use Sakydev\Boring\Resources\Api\CollectionResource;
use Sakydev\Boring\Resources\Api\Responses\BadRequestErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\NotFoundErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\CollectionFieldService;
use Sakydev\Boring\Services\CollectionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CollectionController
{
    public function __construct(
        readonly CollectionFieldService $collectionFieldService,
        readonly CollectionService $collectionService,
    ) {}

    public function show(string $collectionName): JsonResponse {
        try {
            $collection = $this->collectionService->getByName($collectionName);

            return new SuccessResponse('item.success.findOne', [
                'collection' => new CollectionResource($collection),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Fetch collection failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function store(CreateCollectionRequest $createRequest): JsonResponse {
        try {
            $collection = $this->collectionFieldService
                ->storeCollection($createRequest->validated(), Auth::id());

            return new SuccessResponse('item.success.createOne', [
                'collection' => new CollectionResource($collection),
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Create field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
