<?php

namespace App\Ukhu\Application\Ports;

interface CSRFInterface
{
    public function getToken(): string;
}
