<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;

class SuccessResponse extends JsonResponse
{
    public function __construct(
        string|array $message,
        array $data = [],
        int $status = 200,
        array $replace = [],
        array $headers = [],
        int $options = 0,
    ) {
        parent::__construct(
            [
                'status' => 'success',
                'message' => phrase($message, $replace),
                'content' => $data,
            ],
            $status,
            $headers,
            $options,
        );
    }
}
