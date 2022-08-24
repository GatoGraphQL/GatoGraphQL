<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;

trait HasPossibleTypesTypeTrait
{
    /**
     * @return string[]
     */
    public function getPossibleTypeIDs(): array
    {
        $possibleTypeIDs = [];
        /** @var string $objectTypeName */
        foreach (array_keys($this->schemaDefinition[SchemaDefinition::POSSIBLE_TYPES]) as $objectTypeName) {
            $possibleTypeIDs[] = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([
                SchemaDefinition::TYPES,
                TypeKinds::OBJECT,
                $objectTypeName,
            ]);
        }
        return $possibleTypeIDs;
    }
}
