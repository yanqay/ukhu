<?php
require_once __DIR__ . '/../bootstrap/helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

$container = require_once __DIR__ . '/../bootstrap/container.php';

$router = new \League\Route\Router;
// app middleware
$router = (require_once __DIR__ . '/../bootstrap/middleware.php')($router, $container);
// routes
$router = (require_once __DIR__ . '/../bootstrap/routes/web.php')($router, $container);
$router = (require_once __DIR__ . '/../bootstrap/routes/ukhu.php')($router, $container);
$router = (require_once __DIR__ . '/../bootstrap/routes/admin.php')($router, $container);
$router = (require_once __DIR__ . '/../bootstrap/routes/auth.php')($router, $container);

$strategy = new League\Route\Strategy\ApplicationStrategy;
$strategy->setContainer($container);
$router->setStrategy($strategy);

// catch any error
try {
    // dispatch route
    $response = $router->dispatch(
        $container->get('request')
    );

    // emit response
    $sapiEmitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
    $sapiEmitter->emit($response);
} catch (\Throwable $th) {
    $env = $container->get(\App\Ukhu\Application\Ports\EnvInterface::class);
    //display errors
    if ($env->get('APP_DEBUG') === true) {
        $whoops = new Whoops\Run();
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);

        $handler = new Whoops\Handler\PrettyPageHandler();
        $handler->handleUnconditionally(true); // whoops does not know about RoadRunner
        $whoops->prependHandler($handler);
        echo $whoops->handleException($th);
    } else {
        echo '404 not found';
    }
}
