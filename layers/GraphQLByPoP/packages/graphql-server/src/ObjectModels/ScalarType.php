<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class ScalarType extends AbstractType
{
    use NonDocumentableTypeTrait;

    public function __construct(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        protected string $name,
        array $customDefinition = []
    ) {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKind(): string
    {
        return TypeKinds::SCALAR;
    }

    public function getSpecifiedByURL(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::SPECIFIED_BY_URL] ?? null;
    }
}
