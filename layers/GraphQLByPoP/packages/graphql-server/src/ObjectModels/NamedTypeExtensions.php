<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class NamedTypeExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function getTypeElementName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ELEMENT_NAME];
    }

    public function getTypeNamespacedName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAMESPACED_NAME];
    }

    /**
     * Enum-like "possible values" for EnumString type resolvers, `null` otherwise
     *
     * @return string[]|null
     */
    public function getTypePossibleValues(): ?array
    {
        return $this->schemaDefinition[SchemaDefinition::POSSIBLE_VALUES] ?? null;
    }
}
