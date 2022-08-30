<?php

namespace App\Auth\Domain\Exceptions;

class UserNotRegistered extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'User Not Registered';
    }
}
