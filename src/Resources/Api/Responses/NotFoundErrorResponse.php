<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotFoundErrorResponse extends JsonResponse
{
    public function __construct(array|string $error, array $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                'errors' => is_array($error) ? $error : [phrase($error)],
            ],
            Response::HTTP_NOT_FOUND,
            $headers,
            $options,
        );
    }
}
