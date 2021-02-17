<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\Root\Registries\ServiceDefinitionIDRegistryInterface;

interface DataStructureManagerInterface extends ServiceDefinitionIDRegistryInterface
{
    public function getDataStructureFormatter(string $name = null): DataStructureFormatterInterface;
}
