<?php

declare(strict_types=1);

namespace App\Ukhu\Infrastructure\Middlewares;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO review this ErrorMiddleware and the try/catch in index.php
        // both catch a Throwable $th
        try {
            $response = $handler->handle($request);

            // $data = $response->getBody()->__toString();
            // do something with $data
            return $response;
        } catch (\Throwable $th) {
            $response = new Response();
            $response->withStatus(500);
            $response->getBody()->write($this->renderWhoops($th));

            return $response;
        }
    }

    private function renderWhoops(\Throwable $th): string
    {
        $whoops = new Run();
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);

        $handler = new PrettyPageHandler();
        $handler->handleUnconditionally(true); // whoops does not know about RoadRunner

        $whoops->prependHandler($handler);

        return $whoops->handleException($th);
    }
}
