<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Application\Ports\CSRFInterface;
use Exception;

class CSRF implements CSRFInterface
{
    private string $token;
    private int $length;

    public function __construct(int $length = null)
    {
        $this->length = $length ?? 32;

        if ($this->length < 16) {
            throw new Exception('length is not strong enough');
        }

        $this->token = bin2hex(random_bytes($this->length));
    }

    public function getToken(): string
    {
        // since all definitions are resolved once withing the service container
        // then the same instance is retrieved on "$container->get(myclass)" (like a singleton)
        // hence $csrf->getToken() returns SAME generated csrf token for current user session
        return $this->token;
    }
}
