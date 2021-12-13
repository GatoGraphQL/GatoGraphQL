<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

abstract class AbstractNamedType extends AbstractSchemaDefinitionReferenceObject implements NamedTypeInterface
{
    protected NamedTypeExtensions $namedTypeExtensions;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $namedTypeExtensionsSchemaDefinitionPath = array_merge(
            $schemaDefinitionPath,
            [
                SchemaDefinition::EXTENSIONS,
            ]
        );
        $this->namedTypeExtensions = new NamedTypeExtensions($fullSchemaDefinition, $namedTypeExtensionsSchemaDefinitionPath);
    }

    public function getNamespacedName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::EXTENSIONS][SchemaDefinition::NAMESPACED_NAME];
    }

    public function getElementName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::EXTENSIONS][SchemaDefinition::ELEMENT_NAME];
    }

    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }

    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }

    public function getExtensions(): NamedTypeExtensions
    {
        return $this->namedTypeExtensions;
    }
}
