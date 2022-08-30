<?php

namespace App\Auth\Domain\Exceptions;

class IncorrectUserPassword extends \Exception
{
    protected $message;

    function __construct($message = '')
    {
        $this->message = $message ?? 'Incorrect User Password';
    }
}
