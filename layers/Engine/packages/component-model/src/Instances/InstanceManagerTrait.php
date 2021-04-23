<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

trait InstanceManagerTrait
{
    public function getInstanceClass(string $class): string
    {
        return get_class($this->getInstance($class));
    }
}
