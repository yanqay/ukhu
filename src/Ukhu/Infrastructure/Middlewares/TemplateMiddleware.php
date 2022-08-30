<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Middlewares;

use App\Ukhu\Application\Ports\TemplateInterface;
use App\Ukhu\Infrastructure\Adapters\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateMiddleware implements MiddlewareInterface
{
    protected $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Add session global variable to template
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = Session::getSessionFromRequest($request);
        if ($session) {
            $template = $this->container->get(TemplateInterface::class);
            $currentSession = $session->get(Session::USER_SESSION);
            if (!empty($currentSession) && is_array($currentSession)) {
                $template->addGlobal('session', $currentSession);
            }
        }

        $response = $handler->handle($request);

        return $response;
    }
}
