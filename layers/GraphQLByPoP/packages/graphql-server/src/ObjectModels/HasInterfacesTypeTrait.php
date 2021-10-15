<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasInterfacesTypeTrait
{
    /**
     * @return string[]
     */
    public function getInterfaceIDs(): array
    {
        $interfaceIDs = [];
        foreach (array_keys($this->schemaDefinition[SchemaDefinition::INTERFACES]) as $interfaceTypeName) {
            $interfaceIDs[] = SchemaDefinitionHelpers::getID([
                SchemaDefinition::TYPES,
                TypeKinds::INTERFACE,
                $interfaceTypeName,
            ]);
        }
        return $interfaceIDs;
    }
}
