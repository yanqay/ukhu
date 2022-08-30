<?php

namespace App\Auth\Domain\Exceptions;

class EmailAlreadyRegistered extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'User email is already registered';
    }
}
