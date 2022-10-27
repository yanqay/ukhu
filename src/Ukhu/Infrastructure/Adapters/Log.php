<?php

namespace App\Ukhu\Infrastructure\Adapters;

use Psr\Log\LoggerInterface;

class Log implements LoggerInterface
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    public $levels = array(
        'debug' => self::DEBUG,
        'info' => self::INFO,
        'notice' => self::NOTICE,
        'warning' => self::WARNING,
        'error' => self::ERROR,
        'critical' => self::CRITICAL,
        'alert' => self::ALERT,
        'emergency' => self::EMERGENCY,
    );

    private $defaultConfig = [
        "enable" => true,
        "channel" => 'web',
        "directory" => '../var/logs'
    ];
    private $enable;
    private $directory;
    private $channel;
    private $instance;

    function __construct(array $config)
    {
        if (empty($config)) {
            $config = $this->defaultConfig;
        }
        $this->enable = $config['enable'];
        $this->channel = $config['channel'];
        $this->directory = $config['directory'];
        $this->initialize();
    }

    public function initialize()
    {
        $log = new \Monolog\Logger($this->channel);

        $currentDate = date('Y-m-d');
        $logPath = $this->directory . '/' . $currentDate . '.log';

        $log->pushHandler(new \Monolog\Handler\StreamHandler(
            $logPath,
            \Monolog\Logger::DEBUG
        ));

        $this->instance = $log;
    }

    public static function init($message, $context)
    {
        $config = [
            "enable" => true,
            "channel" => 'web',
            "directory" => '../var/logs'
        ];
        $obj = new self($config);
        $obj->enable = $config['enable'];
        $obj->channel = $config['channel'];
        $obj->directory = $config['directory'];

        $log = new \Monolog\Logger($obj->channel);

        $currentDate = date('Y-m-d');
        $logPath = $obj->directory . '/' . $currentDate . '.log';

        $log->pushHandler(new \Monolog\Handler\StreamHandler(
            $logPath,
            \Monolog\Logger::DEBUG
        ));

        $log->info($message, $context);
    }

    public function emergency($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->emergency($message, $context);
        }
    }

    public function alert($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->alert($message, $context);
        }
    }

    public function critical($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->critical($message, $context);
        }
    }

    public function error($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->error($message, $context);
        }
    }

    public function warning($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->warning($message, $context);
        }
    }

    public function notice($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->notice($message, $context);
        }
    }

    public function info($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->info($message, $context);
        }
    }

    public function debug($message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->debug($message, $context);
        }
    }

    public function log($level, $message, array $context = array())
    {
        if ($this->enable === true) {
            $this->instance->log($level, $message, $context);
        }
    }
}
