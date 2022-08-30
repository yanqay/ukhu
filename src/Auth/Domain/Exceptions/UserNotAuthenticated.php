<?php

namespace App\Auth\Domain\Exceptions;

class UserNotAuthenticated extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'User Not Authenticated';
    }
}
