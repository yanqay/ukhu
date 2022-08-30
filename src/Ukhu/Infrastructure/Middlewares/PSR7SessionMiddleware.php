<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PSR7SessionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = \PSR7Sessions\Storageless\Http\SessionMiddleware::fromSymmetricKeyDefaults(
            'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTf=', // replace this with a key of your own (see docs below)
            1200 // 20min
        );

        return $session->process($request, $handler);
    }
}
