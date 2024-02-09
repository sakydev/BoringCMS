<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Http\Requests\Api\CreateFormRequest;
use Sakydev\Boring\Models\Form;
use Sakydev\Boring\Repositories\FormRepository;
use Sakydev\Boring\Resources\Api\FormResource;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FormController extends Controller
{
    public function __construct(readonly FormRepository $formRepository) {}

    public function index(): JsonResponse {
        $forms = new Form();
        $results = $forms->all();

        return new JsonResponse($results, Response::HTTP_OK);
    }

    public function store(CreateFormRequest $createRequest): SuccessResponse|ErrorResponse {
        try {
            $form = $this->formRepository->store($createRequest->validated(), Auth::id());

            return new SuccessResponse('forms.success.store.single', [
                'form' => new FormResource($form),
            ], Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            Log::error('Create form failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('forms.failed.store.unknown');
        }
    }
}
