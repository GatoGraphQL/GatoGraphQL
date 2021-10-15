<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasPossibleTypesTypeTrait
{
    use ResolveTypeSchemaDefinitionReferenceTrait;

    /**
     * @return string[]
     */
    public function getPossibleTypeIDs(): array
    {
        $possibleTypeIDs = [];
        foreach (array_keys($this->schemaDefinition[SchemaDefinition::POSSIBLE_TYPES]) as $objectTypeName) {
            $possibleTypeIDs[] = SchemaDefinitionHelpers::getID([
                SchemaDefinition::TYPES,
                TypeKinds::OBJECT,
                $objectTypeName,
            ]);
        }
        return $possibleTypeIDs;
    }
}
