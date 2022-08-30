<?php

namespace App\Auth\Domain\Exceptions;

class InvalidCSRFToken extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'Invalid CSRF Token';
    }
}
