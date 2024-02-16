<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(array|string $error, int $status, array $replace = [], array $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                'errors' => is_array($error) && empty($replace) ? $error : [phrase($error, $replace)],
            ],
            $status,
            $headers,
            $options,
        );
    }
}
