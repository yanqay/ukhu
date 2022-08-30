<?php

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config.php');
return $builder->build();
