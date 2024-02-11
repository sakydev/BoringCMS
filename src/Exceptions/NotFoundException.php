<?php

namespace Sakydev\Boring\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct(phrase($message), Response::HTTP_NOT_FOUND, $previous);
    }
}
