<?php

namespace App\Ukhu\Domain\Exceptions;

class InternalError extends \Exception
{
    protected $message;

    function __construct(string $message)
    {
        $this->message = $message ?? 'Internal Error';
    }
}
