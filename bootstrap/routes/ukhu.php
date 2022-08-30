<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {
    $router->group('/', function ($router) {
        $router->get('/', \App\Ukhu\Infrastructure\Http\HomeController::class . '::index');
        $router->get('/about', \App\Ukhu\Infrastructure\Http\PageController::class . '::about');
    })
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\TemplateMiddleware($container))
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\WebMiddleware());

    return $router;
};
