<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\API\Registries\SchemaDefinitionRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaDefinitionRegistryFacade
{
    public static function getInstance(): SchemaDefinitionRegistryInterface
    {
        /**
         * @var SchemaDefinitionRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(SchemaDefinitionRegistryInterface::class);
        return $service;
    }
}
