<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Middlewares;

use App\Ukhu\Infrastructure\Adapters\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CSRFMiddleware implements MiddlewareInterface
{
    protected $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Generate CSRF token and add it to current session and template
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // generate csrf and add it to current session
        $session = Session::getSessionFromRequest($request);
        $currentCSRFSession = $session->get(Session::CSRF_SESSION);
        if (empty($currentCSRFSession)) {
            $csrf = $this->container->get(\App\Ukhu\Application\Ports\CSRFInterface::class);
            $session->set(Session::CSRF_SESSION, array(
                'token' => $csrf->getToken()
            ));
        }

        // get csrf from current session and add it to template 
        $template = $this->container->get(\App\Ukhu\Application\Ports\TemplateInterface::class);
        $currentCSRFSession = $session->get(Session::CSRF_SESSION);
        $template->addGlobal('csrf_token', $currentCSRFSession['token']);

        $response = $handler->handle($request);

        return $response;
    }
}
