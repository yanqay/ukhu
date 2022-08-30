<?php

namespace App\Ukhu\Application\Ports;

interface EnvInterface
{
    public function get(string $key);
}
