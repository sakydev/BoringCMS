<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\CreateFieldRequest;
use Sakydev\Boring\Http\Requests\Api\Collection\Field\UpdateFieldRequest;
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

            return new SuccessResponse('item.success.field.field.findMany', [
                'fields' => FieldResource::collection($fields),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('List fields failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function show(Request $request): JsonResponse {
        try {
            $field = $this->fieldService->getByUUID($request->route('fieldUUID'));

            return new SuccessResponse('item.success.field.findOne', [
                'field' => new FieldResource($field),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Fetch field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function store(CreateFieldRequest $createRequest, string $collectionName): JsonResponse {
        try {
            $userId = Auth::id();
            $field = $this->collectionFieldService->storeField($createRequest->validated(), $collectionName, $userId);

            return new SuccessResponse('item.success.field.createOne', [
                'field' => new FieldResource($field),
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Create field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function update(UpdateFieldRequest $updateRequest): JsonResponse {
        try {
            $userId = Auth::id();
            $updatedFields = $updateRequest->only(['validation', 'condition', 'is_required']);

            $field = $this->fieldService->update($updatedFields, $updateRequest->route('fieldUUID'), $userId);

            return new SuccessResponse('item.success.field.updateOne', [
                'field' => new FieldResource($field),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Update field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function destroy(string $collectionName, string $fieldUUID): JsonResponse
    {
        try {
            $this->collectionFieldService->destroyField($fieldUUID, $collectionName);

            return new SuccessResponse('item.success.field.destroyOne', [], Response::HTTP_NO_CONTENT);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Delete field failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
