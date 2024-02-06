<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(array|string $error, int $status, array $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                'errors' => is_array($error) ? $error : [phrase($error)],
            ],
            $status,
            $headers,
            $options,
        );
    }
}
