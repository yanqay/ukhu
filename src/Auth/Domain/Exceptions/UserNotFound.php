<?php

namespace App\Auth\Domain\Exceptions;

class UserNotFound extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'User Not Found';
    }
}
