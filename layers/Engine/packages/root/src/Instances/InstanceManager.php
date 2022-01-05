<?php

declare(strict_types=1);

namespace PoP\Root\Instances;

use PoP\Root\App;
use PoP\Root\Container\ContainerBuilderFactory;

class InstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = App::getContainerBuilderFactory()->getInstance();
        return $containerBuilder->get($class);
    }

    public function hasInstance(string $class): bool
    {
        $containerBuilder = App::getContainerBuilderFactory()->getInstance();
        return $containerBuilder->has($class);
    }
}
