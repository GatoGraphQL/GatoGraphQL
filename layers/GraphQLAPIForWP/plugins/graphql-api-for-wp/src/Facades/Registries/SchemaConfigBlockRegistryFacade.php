<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;

class SchemaConfigBlockRegistryFacade
{
    public static function getInstance(): SchemaConfigBlockRegistryInterface
    {
        /**
         * @var SchemaConfigBlockRegistryInterface
         */
        $service = App::getContainer()->get(SchemaConfigBlockRegistryInterface::class);
        return $service;
    }
}
