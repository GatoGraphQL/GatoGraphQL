<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

class InputObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ) {
        parent::__construct($inputObjectTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::INPUT_OBJECT;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();


        return $schemaDefinition;
    }
}
