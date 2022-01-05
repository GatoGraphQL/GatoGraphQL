<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Registries;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;

class SchemaDefinitionReferenceRegistryFacade
{
    public static function getInstance(): SchemaDefinitionReferenceRegistryInterface
    {
        /**
         * @var SchemaDefinitionReferenceRegistryInterface
         */
        $service = App::getContainer()->get(SchemaDefinitionReferenceRegistryInterface::class);
        return $service;
    }
}
