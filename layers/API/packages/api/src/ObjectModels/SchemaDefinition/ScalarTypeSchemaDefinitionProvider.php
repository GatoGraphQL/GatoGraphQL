<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;

class ScalarTypeSchemaDefinitionProvider extends AbstractNamedTypeSchemaDefinitionProvider
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
