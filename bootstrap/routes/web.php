<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {

    $router->get('/test', function ($test) {
        $response = new \Laminas\Diactoros\Response();
        $response->getBody()->write('testing');
        return $response;
    });

    return $router;
};
