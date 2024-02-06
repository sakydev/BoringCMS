<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Symfony\Component\HttpFoundation\Response;

class ExceptionErrorResponse extends ErrorResponse
{
    public function __construct(array|string $error, array $headers = [], int $options = 0) {
        parent::__construct(
            is_array($error) ? $error : phrase($error),
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $headers,
            $options,
        );
    }
}
