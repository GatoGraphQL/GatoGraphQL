<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MetaDirectiveRegistryFacade
{
    public static function getInstance(): MetaDirectiveRegistryInterface
    {
        /**
         * @var MetaDirectiveRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(MetaDirectiveRegistryInterface::class);
        return $service;
    }
}
