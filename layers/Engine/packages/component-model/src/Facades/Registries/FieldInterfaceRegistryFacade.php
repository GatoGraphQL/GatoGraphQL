<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FieldInterfaceRegistryFacade
{
    public static function getInstance(): FieldInterfaceRegistryInterface
    {
        /**
         * @var FieldInterfaceRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(FieldInterfaceRegistryInterface::class);
        return $service;
    }
}
