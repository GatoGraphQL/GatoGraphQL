<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\ContainerBuilderFactory;

trait InstanceManagerTrait
{
    public function getInstanceClass(string $class): string
    {
        return get_class($this->getInstance($class));
    }

    public function getInstance(string $class): object
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        return $containerBuilder->get($class);
    }
}
