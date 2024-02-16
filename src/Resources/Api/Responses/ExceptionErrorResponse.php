<?php

namespace Sakydev\Boring\Resources\Api\Responses;

use Symfony\Component\HttpFoundation\Response;

class ExceptionErrorResponse extends ErrorResponse
{
    public function __construct(array|string $error, array $replace = [], array $headers = [], int $options = 0) {
        parent::__construct(
            $error,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $replace,
            $headers,
            $options,
        );
    }
}
