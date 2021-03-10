<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

use PoP\Root\Container\ContainerBuilderFactory;

trait InstanceManagerTrait
{
    /**
     * @var array<string, object>
     */
    private array $instances = [];

    protected function hasClassBeenLoaded(string $class): bool
    {
        return !is_null($this->instances[$class] ?? null);
    }

    public function getImplementationClass(string $class): string
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $instance = $containerBuilder->get($class);
        return get_class($instance);
    }

    public function getClassInstance(string $class): object
    {
        if (!$this->hasClassBeenLoaded($class)) {
            $containerBuilder = ContainerBuilderFactory::getInstance();
            if ($containerBuilder->has($class)) {
                $instance = $containerBuilder->get($class);
            } else {
                // Otherwise, assume the class needs no parameters
                $instance = new $class();
            }
            $this->instances[$class] = $instance;
        }

        return $this->instances[$class];
    }

    public function getInstance(string $class): object
    {
        return $this->getClassInstance($class);
    }
}
