<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class Directive extends AbstractSchemaDefinitionReferenceObject
{
    use HasArgsSchemaDefinitionReferenceTrait;

    protected DirectiveExtensions $directiveExtensions;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        /** @var string[] */
        $directiveExtensionsSchemaDefinitionPath = array_merge(
            $schemaDefinitionPath,
            [
                SchemaDefinition::EXTENSIONS,
            ]
        );
        $this->directiveExtensions = new DirectiveExtensions($fullSchemaDefinition, $directiveExtensionsSchemaDefinitionPath);

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

    /**
     * @return string[]
     */
    public function getLocations(): array
    {
        return $this->schemaDefinition[SchemaDefinition::DIRECTIVE_LOCATIONS];
    }

    public function isRepeatable(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::DIRECTIVE_IS_REPEATABLE];
    }

    public function getKind(): string
    {
        return $this->schemaDefinition[SchemaDefinition::DIRECTIVE_KIND];
    }

    public function getExtensions(): DirectiveExtensions
    {
        return $this->directiveExtensions;
    }
}
