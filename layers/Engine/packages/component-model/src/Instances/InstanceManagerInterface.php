<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Instances;

interface InstanceManagerInterface
{
    public function getInstance(string $class): object;
}
