<?php
if (!function_exists('error_handler_prod')) {
    function error_handler_prod($severity, $message, $filename, $lineno)
    {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            $e = new \ErrorException($message, 0, $severity, $filename, $lineno);
            $fullErrorMsg = <<<TXT
            {$e->getMessage()}
            {$e->getTraceAsString()}
            TXT;
            App\Ukhu\Infrastructure\Adapters\Log::init('franck', array($fullErrorMsg));

            // after logging error, it continues to dispatch route 
        }
    }
}
if (!function_exists('error_handler_debug')) {
    function error_handler_debug($severity, $message, $filename, $lineno)
    {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            $whoops = new Whoops\Run();
            $whoops->allowQuit(false);
            $whoops->writeToOutput(false);

            $handler = new Whoops\Handler\PrettyPageHandler();
            $handler->handleUnconditionally(true); // whoops does not know about RoadRunner
            $whoops->prependHandler($handler);
            $e = new \ErrorException($message, 0, $severity, $filename, $lineno);
            echo $whoops->handleException($e);

            // after throwing exception, stop dispatching route
            exit;
        }
    }
}
if (!function_exists('pp')) {
    function pp()
    {
        $args = func_get_args();

        if (!empty($args)) {
            echo '<pre>';
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();

        if (!empty($args)) {
            echo '<pre>';
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo '</pre>';
        }
        exit;
    }
}
