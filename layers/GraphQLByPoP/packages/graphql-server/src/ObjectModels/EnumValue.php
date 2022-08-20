<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class EnumValue extends AbstractSchemaDefinitionReferenceObject
{
    protected EnumValueExtensions $enumValueExtensions;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $enumValueExtensionsSchemaDefinitionPath = array_merge(
            $schemaDefinitionPath,
            [
                SchemaDefinition::EXTENSIONS,
            ]
        );
        $this->enumValueExtensions = new EnumValueExtensions($fullSchemaDefinition, $enumValueExtensionsSchemaDefinitionPath);
    }

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
    public function getExtensions(): EnumValueExtensions
    {
        return $this->enumValueExtensions;
    }
}
