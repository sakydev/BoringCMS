<?php

namespace Sakydev\Boring\Http\Controllers\Api\Collection;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Resources\Api\EntryResource;
use Sakydev\Boring\Resources\Api\FieldResource;
use Sakydev\Boring\Resources\Api\Responses\BadRequestErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\ExceptionErrorResponse;
use Sakydev\Boring\Resources\Api\Responses\SuccessResponse;
use Sakydev\Boring\Services\EntryService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EntryController
{
    public function __construct(
        readonly EntryService $entryService,
    ) {}

    public function store(Request $createRequest, string $collectionName): JsonResponse {
        try {
            $entry = $this->entryService->store($createRequest->all(), $collectionName);

            return new SuccessResponse('item.success.entry.createOne', [
                'entry' => new EntryResource($entry),
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $exception) {
            return new BadRequestErrorResponse($exception->getMessage());
        } catch (Throwable $throwable) {
            dd($throwable);
            Log::error('Create entry failed', ['error' => $throwable->getMessage()]);

            return new ExceptionErrorResponse('general.error.unknown');
        }
    }
}
