<?php

namespace App\Ukhu\Infrastructure\Adapters;

class DTOMapper
{
    public function mapCollectionToClass(array $params, string $givenClass)
    {
        $multipleItems = [];
        foreach ($params as $item) {
            $multipleItems[] = $this->map($params, $givenClass);
        }
        return $multipleItems;
    }

    public function mapArrayToClass(array $params, string $givenClass)
    {
        return $this->map($params, $givenClass);
    }

    private function map(array $params, string $givenClass)
    {
        // Construct a reflection method from the constructor and get all its parameters
        $reflectionMethod = new \ReflectionMethod($givenClass, '__construct');
        $reflectionParameters = $reflectionMethod->getParameters();
        $parameters = array();

        // Iterate parameters and match them with $params array keys
        foreach ($reflectionParameters as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();

            // If array key is not found in the constructor, throw exception
            if (!array_key_exists($parameterName, $params) && !$reflectionParameter->isOptional()) {
                throw new \Exception(
                    'Unable to instantiate \'' . $givenClass . '\' from given array. Argument "' . $parameterName . '" is missing. Only following arguments are available: ' . implode(', ', \array_keys($params))
                );
            }

            $parameters[] = $params[$parameterName] ?? $reflectionParameter->getDefaultValue();
        }

        return new $givenClass(...$parameters);
    }
}
