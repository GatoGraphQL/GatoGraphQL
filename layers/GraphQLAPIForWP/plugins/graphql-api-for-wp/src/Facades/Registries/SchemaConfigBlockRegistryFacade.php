<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;

class SchemaConfigBlockRegistryFacade
{
    public static function getInstance(): SchemaConfigBlockRegistryInterface
    {
        /**
         * @var SchemaConfigBlockRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(SchemaConfigBlockRegistryInterface::class);
        return $service;
    }
}
