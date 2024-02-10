<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\CreateFormRequest;
use Sakydev\Boring\Http\Requests\Api\UpdateFormRequest;
use Sakydev\Boring\Models\Form;
use Sakydev\Boring\Repositories\FormRepository;
use Sakydev\Boring\Resources\Api\FormResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\FormService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FormController extends Controller
{
    public function __construct(
        readonly FormRepository $formRepository,
        readonly FormService $formService
    ) {}

    public function index(Request $request): SuccessResponse|ErrorResponse {
        try {
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 20);

            $page = max(1, (int)$page);
            $limit = max(1, min(100, (int)$limit));

            $forms = $this->formRepository->listByUser(Auth::id(), $page, $limit);

            return new SuccessResponse('forms.success.find.list', [
                'forms' => FormResource::collection($forms),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('List forms failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.find.unknown');
        }
    }

    public function show(string $slug): SuccessResponse|ErrorResponse {
        try {
            $form = $this->formRepository->getBySlugAndUser($slug, Auth::id());
            if (!$form) {
                return new ErrorResponse(
                    'forms.failed.find.single',
                    Response::HTTP_NOT_FOUND
                );
            }

            return new SuccessResponse('forms.success.find.single', [
                'form' => new FormResource($form),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('Fetch form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.find.unknown');
        }
    }

    public function store(CreateFormRequest $createRequest): SuccessResponse|ErrorResponse {
        try {
            $form = $this->formRepository->store($createRequest->validated(), Auth::id());

            return new SuccessResponse('forms.success.store.single', [
                'form' => new FormResource($form),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            dd($throwable);
            Log::error('Create form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.store.unknown');
        }
    }

    public function update(UpdateFormRequest $updateRequest, $slug): SuccessResponse|ErrorResponse
    {
        try {
            $userId = Auth::id();
            $updatedFields = $updateRequest->only(['name', 'slug']);

            $form = $this->formRepository->getBySlugAndUser($slug, $userId);
            if (!$form) {
                return new ErrorResponse(
                    'forms.failed.find.single',
                    Response::HTTP_NOT_FOUND
                );
            }

            $form = $this->formRepository->update($form, $updatedFields);

            return new SuccessResponse('forms.success.update.single', [
                'form' => new FormResource($form),
            ], Response::HTTP_OK);
        } catch (Throwable $throwable) {
            Log::error('Update form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.update.unknown');
        }
    }

    public function destroy(string $slug): SuccessResponse|ErrorResponse
    {
        try {
            if (!$this->formRepository->existsBySlugAndUser($slug, Auth::id())) {
                return new ErrorResponse(
                    'forms.failed.find.single',
                    Response::HTTP_NOT_FOUND
                );
            }

            $this->formRepository->destroyBySlugAndUser($slug, Auth::id());

            return new SuccessResponse('forms.success.delete.single', [], Response::HTTP_NO_CONTENT);
        } catch (Throwable $throwable) {
            Log::error('Delete form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.delete.unknown');
        }
    }
}
