<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {
    $router->group('/', function ($router) {
        // register
        $router->get('/register', \App\Auth\Infrastructure\Http\AuthController::class . '::showRegistrationForm');
        $router->post('/register', \App\Auth\Infrastructure\Http\RegisterUserController::class . '::handle');

        // auth
        $router->get('/login', \App\Auth\Infrastructure\Http\AuthController::class . '::showLogin');
        $router->post('/login', \App\Auth\Infrastructure\Http\LoginUserController::class . '::handle');
    })
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\TemplateMiddleware($container))
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\WebMiddleware());

    // with session
    $router->group('/', function ($router) {
        $router->post('/password/email', \App\Auth\Infrastructure\Http\AuthController::class . '::sendResetLinkEmail');
        $router->get('/password/reset', \App\Auth\Infrastructure\Http\AuthController::class . '::showLinkRequestForm');
        $router->post('/password/reset', \App\Auth\Infrastructure\Http\AuthController::class . '::reset');
        $router->get('/password/reset/{token}', \App\Auth\Infrastructure\Http\AuthController::class . '::showResetForm');
    })
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\TemplateMiddleware($container))
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\AuthMiddleware);

    return $router;
};
