<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Registries;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;

class SchemaDefinitionReferenceRegistryFacade
{
    public static function getInstance(): SchemaDefinitionReferenceRegistryInterface
    {
        /**
         * @var SchemaDefinitionReferenceRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(SchemaDefinitionReferenceRegistryInterface::class);
        return $service;
    }
}
