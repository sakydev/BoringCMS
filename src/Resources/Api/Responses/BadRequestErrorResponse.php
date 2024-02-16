<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BadRequestErrorResponse extends JsonResponse
{
    public function __construct(array|string $error, array $replace  = [], array $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                'errors' => $error,
            ],
            Response::HTTP_BAD_REQUEST,
            $headers,
            $options,
        );
    }
}
