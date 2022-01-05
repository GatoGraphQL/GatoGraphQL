<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TypeRegistryFacade
{
    public static function getInstance(): TypeRegistryInterface
    {
        /**
         * @var TypeRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(TypeRegistryInterface::class);
        return $service;
    }
}
