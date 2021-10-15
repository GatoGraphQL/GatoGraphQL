<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class ScalarType extends AbstractType
{
    public function getKind(): string
    {
        return TypeKinds::SCALAR;
    }

    public function getSpecifiedByURL(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::SPECIFIED_BY_URL] ?? null;
    }
}
