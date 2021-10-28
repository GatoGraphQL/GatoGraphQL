<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

abstract class AbstractSchemaDefinitionReferenceObject implements SchemaDefinitionReferenceObjectInterface
{
    protected string $id;

    /**
     * Build and register the object in the registry
     */
    public function __construct()
    {
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $schemaDefinitionReferenceRegistry->registerSchemaDefinitionReferenceObject($this);
    }

    public function getID(): string
    {
        return $this->id;
    }
}
