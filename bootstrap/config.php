<?php

return [
    \App\Ukhu\Application\Ports\CSRFInterface::class => function () {
        return new \App\Ukhu\Infrastructure\Adapters\CSRF;
    },
    'request' => function () {
        return Laminas\Diactoros\ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    },
    \App\Ukhu\Application\Ports\EnvInterface::class => function () {
        return new \App\Ukhu\Infrastructure\Adapters\Env(__DIR__ . '/../');
    },
    \Psr\Log\LoggerInterface::class => function (Psr\Container\ContainerInterface $c) {
        $env = $c->get(\App\Ukhu\Application\Ports\EnvInterface::class);

        return new \App\Ukhu\Infrastructure\Adapters\Log([
            "enable" => $env->get('ENABLE_LOG'),
            "channel" => 'web',
            "directory" => '../var/logs'
        ]);
    },
    PDO::class => function (Psr\Container\ContainerInterface $c) {
        $database = new \App\Ukhu\Infrastructure\Adapters\Database($c->get(\App\Ukhu\Application\Ports\EnvInterface::class));
        return $database->getConnection();
    },
    \App\Ukhu\Infrastructure\Adapters\Mailer::class => function (Psr\Container\ContainerInterface $c) {
        $env = $c->get(\App\Ukhu\Application\Ports\EnvInterface::class);
        $template = $c->get(\App\Ukhu\Application\Ports\TemplateInterface::class);
        return new \App\Ukhu\Infrastructure\Adapters\Mailer($env, $template);
    },
    \App\Ukhu\Application\Ports\TemplateInterface::class => function (Psr\Container\ContainerInterface $c) {
        $env = $c->get(\App\Ukhu\Application\Ports\EnvInterface::class);

        $url                = $env->get('APP_URL');
        $debug              = $env->get('APP_DEBUG');
        $cacheLocation      = '../var/cache';
        $templateLocations   = array(
            '../src/Auth/Infrastructure/Http/Presentation/templates',
            '../src/Ukhu/Infrastructure/Http/Presentation/templates',
        );
        $manifestFile       = '../public/assets/manifest.json';
        $publicDir          = '../public';
        $cache              = $env->get('CACHE_TEMPLATE');

        return new \App\Ukhu\Infrastructure\Adapters\TwigTemplate(
            $url,
            $debug,
            $templateLocations,
            $cache,
            $cacheLocation,
            $manifestFile,
            $publicDir
        );
    }
];