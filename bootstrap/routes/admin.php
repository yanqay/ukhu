<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {

    $router->group('/admin', function ($router) {
        $router->get('/', \App\Ukhu\Infrastructure\Http\AdminController::class . '::index');

        $router->get('/logout', \App\Auth\Infrastructure\Http\AuthController::class . '::logout');

        // settings
        $router->get('/settings', \App\Auth\Infrastructure\Http\SettingsController::class . '::settings');
    })
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\TemplateMiddleware($container))
        ->middleware(new \App\Ukhu\Infrastructure\Middlewares\AuthMiddleware);

    return $router;
};
