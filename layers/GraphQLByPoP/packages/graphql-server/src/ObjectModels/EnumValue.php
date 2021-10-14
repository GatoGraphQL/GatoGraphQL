<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class EnumValue extends AbstractSchemaDefinitionReferenceObject
{
    public function getName(): string
    {
        return $this->getValue();
    }
    public function getValue(): string
    {
        return $this->schemaDefinition[SchemaDefinition::VALUE];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }
    public function isDeprecated(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATED] ?? false;
    }
    public function getDeprecatedReason(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] ?? null;
    }
}
