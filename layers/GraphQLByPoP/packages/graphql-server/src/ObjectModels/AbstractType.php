<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

abstract class AbstractType extends AbstractSchemaDefinitionReferenceObject
{
    abstract public function getKind(): string;

    /**
     * Once all types are initialized, call this function to further link to other types
     */
    public function initializeTypeDependencies(): void
    {
    }

    public function getNamespacedName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAMESPACED_NAME];
    }

    public function getElementName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ELEMENT_NAME];
    }

    /**
     * Static types: their names are defined under property "name"
     * from the schema definition
     */
    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }
    /**
     * There are no extensions currently implemented for the Type
     */
    public function getExtensions(): array
    {
        return [];
    }
}
