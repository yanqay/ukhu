<?php

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Application\Ports\EnvInterface;
use Dotenv\Dotenv;

class Env implements EnvInterface
{
    private $envDirectory;
    private $values;

    function __construct($envDirectory)
    {
        $this->envDirectory = $envDirectory;

        $this->values = $this->getEnvironmentValues();
    }

    public function get(string $key)
    {
        return $this->values[$key] ?? null;
    }

    private function getEnvironmentValues()
    {
        $values = [];

        //$dotenv = Dotenv::createImmutable('../');
        $dotenv = Dotenv::createMutable($this->envDirectory);
        $loadedVariables = $dotenv->load();

        foreach ($loadedVariables as $key => $value) {
            $values[$key] = self::convertValue($value);
        }

        return $values;
    }

    private static function convertValue($value)
    {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
            default:
                /* return not lowercased value (as is) */
                return $value;
        }
    }
}
