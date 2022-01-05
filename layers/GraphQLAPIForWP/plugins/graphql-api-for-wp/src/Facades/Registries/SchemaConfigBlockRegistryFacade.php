<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaConfigBlockRegistryFacade
{
    public static function getInstance(): SchemaConfigBlockRegistryInterface
    {
        /**
         * @var SchemaConfigBlockRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(SchemaConfigBlockRegistryInterface::class);
        return $service;
    }
}
