<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {
    $router->middleware(new \App\Ukhu\Infrastructure\Middlewares\ErrorMiddleware);
    $router->middleware(new \App\Ukhu\Infrastructure\Middlewares\PSR7SessionMiddleware);
    $router->middleware(new \App\Ukhu\Infrastructure\Middlewares\CSRFMiddleware($container));
    return $router;
};
