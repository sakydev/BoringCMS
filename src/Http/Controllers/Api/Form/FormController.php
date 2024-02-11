<?php

namespace Sakydev\Boring\Http\Controllers\Api\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Http\Requests\Api\Form\CreateFormRequest;
use Sakydev\Boring\Http\Requests\Api\Form\UpdateFormRequest;
use Sakydev\Boring\Repositories\FormRepository;
use Sakydev\Boring\Resources\Api\FormResource;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\NotFoundErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\FormService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FormController extends Controller
{
    public function __construct(
        readonly FormService $formService
    ) {}

    public function index(Request $request): JsonResponse {
        try {
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 20);

            $page = max(1, (int)$page);
            $limit = max(1, min(100, (int)$limit));

            $forms = $this->formService->listByUser(Auth::id(), $page, $limit);

            return new SuccessResponse('item.success.findMany', [
                'forms' => FormResource::collection($forms),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('List forms failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function show(string $slug): JsonResponse {
        try {
            $userId = Auth::id();
            $form = $this->formService->getBySlugAndUser($slug, $userId);

            return new SuccessResponse('item.success.findOne', [
                'form' => new FormResource($form),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Fetch form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function store(CreateFormRequest $createRequest): JsonResponse {
        try {
            $form = $this->formService->store($createRequest->validated(), Auth::id());

            return new SuccessResponse('item.success.createOne', [
                'form' => new FormResource($form),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            Log::error('Create form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function update(UpdateFormRequest $updateRequest, $slug): JsonResponse
    {
        try {
            $userId = Auth::id();
            $updatedFields = $updateRequest->only(['name', 'slug']);

            $form = $this->formService->updateBySlugAndUser($updatedFields, $slug, $userId);

            return new SuccessResponse('item.success.updateOne', [
                'form' => new FormResource($form),
            ], Response::HTTP_OK);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Update form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }

    public function destroy(string $slug): JsonResponse
    {
        try {
            $this->formService->destroyBySlugAndUser($slug, Auth::id());

            return new SuccessResponse('item.success.destroyOne', [], Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $exception) {
            return new NotFoundErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            Log::error('Delete form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
