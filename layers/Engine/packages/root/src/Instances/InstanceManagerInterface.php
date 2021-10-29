<?php

declare(strict_types=1);

namespace PoP\Root\Instances;

interface InstanceManagerInterface
{
    public function getInstance(string $class): object;
    public function hasInstance(string $class): bool;
}
