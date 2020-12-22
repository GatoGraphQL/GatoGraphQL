<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;

abstract class AbstractType extends AbstractSchemaDefinitionReferenceObject
{
    abstract public function getKind(): string;

    /**
     * Once all types are initialized, call this function to further link to other types
     *
     * @return void
     */
    public function initializeTypeDependencies(): void
    {
    }

    public function getNamespacedName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_NAMESPACED_NAME];
    }

    public function getElementName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_ELEMENT_NAME];
    }

    /**
     * Static types: their names are defined under property "name"
     * from the schema definition
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION];
    }
    /**
     * There are no extensions currently implemented for the Type
     *
     * @return array
     */
    public function getExtensions(): array
    {
        return [];
    }
}
