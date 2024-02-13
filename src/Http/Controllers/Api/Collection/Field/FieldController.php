<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection\Field;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\CreateFieldRequest;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\UpdatedFieldRequest;
use Sakydev\Boring\Resources\Api\FieldResource;
use Sakydev\Boring\Resources\Api\Responses\BadRequestErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\NotFoundErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\CollectionFieldService;
use Sakydev\Boring\Services\FieldService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FieldController
{
    public function __construct(
        readonly FieldService $fieldService,
        readonly CollectionFieldService $collectionFieldService
    ) {}

    public function index(Request $request): JsonResponse {
        try {
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 20);

            $page = max(1, (int)$page);
            $limit = max(1, min(100, (int)$limit));

            $fields = $this->fieldService->list($page, $limit);

            return new SuccessResponse('item.success.findMany', [
                'fields' => FieldResource::collection($fields),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('List fields failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function show(string $uuid): JsonResponse {
        try {
            $form = $this->fieldService->getByUUID($uuid);

            return new SuccessResponse('item.success.findOne', [
                'field' => new FieldResource($form),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Fetch field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function store(CreateFieldRequest $createRequest, string $collectionName): JsonResponse {
        try {
            $userId = Auth::id();
            $field = $this->collectionFieldService->storeField($createRequest->validated(), $collectionName, $userId);

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

    public function update(UpdatedFieldRequest $updateRequest, $slug): JsonResponse
    {
        try {
            $userId = Auth::id();
            $updatedFields = $updateRequest->only(['validation', 'condition', 'is_required']);

            $form = $this->fieldService->update($updatedFields, $slug, $userId);

            return new SuccessResponse('item.success.updateOne', [
                'field' => new FieldResource($form),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Update field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function destroy(string $fieldUUID): JsonResponse
    {
        try {
            $this->collectionFieldService->destroyField($fieldUUID);

            return new SuccessResponse('item.success.destroyOne', [], Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Delete field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
