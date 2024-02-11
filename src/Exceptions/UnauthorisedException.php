<?php

namespace Sakydev\Boring\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorisedException extends Exception
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct(phrase($message), Response::HTTP_UNAUTHORIZED, $previous);
    }
}
