<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\SystemContainerBuilderFactory;

class SystemInstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = SystemContainerBuilderFactory::getInstance();
        return $containerBuilder->get($class);
    }
}
