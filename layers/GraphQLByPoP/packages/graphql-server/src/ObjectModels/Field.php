<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;

class Field extends AbstractSchemaDefinitionReferenceObject
{
    use HasTypeSchemaDefinitionReferenceTrait;
    use HasArgsSchemaDefinitionReferenceTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }
    public function isDeprecated(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATED] ?? false;
    }
    public function getDeprecationMessage(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] ?? null;
    }
    public function getExtensions(): array
    {
        $extensions = $this->schemaDefinition[SchemaDefinition::EXTENSIONS] ?? [];
        if ($version = $this->schemaDefinition[SchemaDefinition::VERSION] ?? null) {
            $extensions[SchemaDefinition::VERSION] = $version;
        }
        if ($this->schemaDefinition[SchemaDefinition::FIELD_IS_MUTATION] ?? null) {
            $extensions[SchemaDefinition::FIELD_IS_MUTATION] = true;
        }
        if ($this->schemaDefinition[SchemaDefinition::IS_ADMIN_ELEMENT] ?? null) {
            $extensions[SchemaDefinition::IS_ADMIN_ELEMENT] = true;
        }
        return $extensions;
    }
}
