<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;

trait HasInterfacesTypeTrait
{
    /**
     * @return string[]
     */
    public function getInterfaceIDs(): array
    {
        $interfaceIDs = [];
        /** @var string $interfaceTypeName */
        foreach (array_keys($this->schemaDefinition[SchemaDefinition::INTERFACES] ?? []) as $interfaceTypeName) {
            $interfaceIDs[] = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([
                SchemaDefinition::TYPES,
                TypeKinds::INTERFACE,
                $interfaceTypeName,
            ]);
        }
        return $interfaceIDs;
    }
}
