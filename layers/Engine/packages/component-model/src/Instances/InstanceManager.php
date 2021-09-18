<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\ContainerBuilderFactory;

class InstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        return $containerBuilder->get($class);
    }
}
