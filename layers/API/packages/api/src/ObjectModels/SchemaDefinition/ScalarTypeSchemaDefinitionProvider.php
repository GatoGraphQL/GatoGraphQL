<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;

class ScalarTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected ScalarTypeResolverInterface $scalarTypeResolver,
    ) {
        parent::__construct($scalarTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::SCALAR;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addScalarSchemaDefinition($schemaDefinition);

        return $schemaDefinition;
    }

    final protected function addScalarSchemaDefinition(array &$schemaDefinition): void
    {
        if ($specifiedByURL = $this->scalarTypeResolver->getSpecifiedByURL()) {
            $schemaDefinition[SchemaDefinition::SPECIFIED_BY_URL] = $specifiedByURL;
        }
    }
}
