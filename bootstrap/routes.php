<?php

use DI\Container;
use League\Route\Router;

return function (Router $router, Container $container): Router {
    $router = (require_once __DIR__ . '/../bootstrap/routes/web.php')($router, $container);
    $router = (require_once __DIR__ . '/../bootstrap/routes/ukhu.php')($router, $container);
    $router = (require_once __DIR__ . '/../bootstrap/routes/admin.php')($router, $container);
    $router = (require_once __DIR__ . '/../bootstrap/routes/auth.php')($router, $container);

    return $router;
};