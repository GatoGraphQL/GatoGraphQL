<?php

declare(strict_types=1);

namespace PoP\Root\Instances;

use PoP\Root\App;

class SystemInstanceManager implements InstanceManagerInterface
{
    public function getInstance(string $class): object
    {
        $containerBuilder = App::getSystemContainer();
        return $containerBuilder->get($class);
    }

    public function hasInstance(string $class): bool
    {
        $containerBuilder = App::getSystemContainer();
        return $containerBuilder->has($class);
    }
}
