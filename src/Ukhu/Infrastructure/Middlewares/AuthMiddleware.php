<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Middlewares;

use App\Ukhu\Infrastructure\Adapters\Session;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * limit access to auth users
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(\PSR7Sessions\Storageless\Http\SessionMiddleware::SESSION_ATTRIBUTE);
        $currentSession = $session->get(Session::USER_SESSION);
        if (!empty($currentSession) && isset($currentSession['user']) && is_array($currentSession['user'])) {
            return $handler->handle($request);
        }

        return new RedirectResponse('/', 302); // 401 does not work
    }
}
