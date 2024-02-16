<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UnprocessableRequestErrorResponse extends ErrorResponse
{
    public function __construct(array|string $error, array $replace = [], $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                $error,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $replace,
            $headers,
            $options,
        );
    }
}
