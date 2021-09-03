<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractTypeDataLoader;

class SchemaDefinitionReferenceTypeDataLoader extends AbstractTypeDataLoader
{
    /**
     * @return AbstractSchemaDefinitionReferenceObject[]
     */
    public function getObjects(array $ids): array
    {
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        // Filter out potential `null` results
        return array_filter(array_map(
            fn ($schemaDefinitionID) => $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID),
            $ids
        ));
    }
}
