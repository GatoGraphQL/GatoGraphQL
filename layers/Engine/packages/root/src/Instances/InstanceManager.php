<?php

declare(strict_types=1);

namespace PoP\Root\Instances;

use PoP\Root\App;

class InstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = App::getContainer();
        return $containerBuilder->get($class);
    }

    public function hasInstance(string $class): bool
    {
        $containerBuilder = App::getContainer();
        return $containerBuilder->has($class);
    }
}
