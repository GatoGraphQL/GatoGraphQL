<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

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
