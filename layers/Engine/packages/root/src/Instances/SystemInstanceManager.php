<?php

declare(strict_types=1);

namespace PoP\Root\Instances;

use PoP\Root\Container\SystemContainerBuilderFactory;

class SystemInstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = \PoP\Root\App::getSystemContainerBuilderFactory()->getInstance();
        return $containerBuilder->get($class);
    }

    public function hasInstance(string $class): bool
    {
        $containerBuilder = \PoP\Root\App::getSystemContainerBuilderFactory()->getInstance();
        return $containerBuilder->has($class);
    }
}
