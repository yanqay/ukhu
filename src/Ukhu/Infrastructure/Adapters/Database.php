<?php

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Application\Ports\EnvInterface;
use PDO;

class Database
{
    private $settings;

    public function __construct(EnvInterface $env)
    {
        $this->settings = array (
            'driver' => $env->get('DB_CONNECTION'),
            'host' => $env->get('DB_HOST'),
            'username' => $env->get('DB_USERNAME'),
            'database' => $env->get('DB_DATABASE'),
            'password' => $env->get('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'flags' => [
                // Turn off persistent connections
                PDO::ATTR_PERSISTENT => false,
                // Enable exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Emulate prepared statements
                PDO::ATTR_EMULATE_PREPARES => true,
                // Set default fetch mode to array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ],
        );
    }

    public function getConnection()
    {
        $host = $this->settings['host'];
        $dbname = $this->settings['database'];
        $username = $this->settings['username'];
        $password = $this->settings['password'];
        $charset = $this->settings['charset'];
        $flags = $this->settings['flags'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password);
    }
}
