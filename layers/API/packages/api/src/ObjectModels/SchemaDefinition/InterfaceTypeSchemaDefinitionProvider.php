<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class InterfaceTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected InterfaceTypeResolverInterface $interfaceTypeResolver,
    ) {
        parent::__construct($interfaceTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_INTERFACE;
    }
    
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $schemaDefinition[SchemaDefinition::FIELDS] = $this->interfaceTypeResolver->getFieldNamesToImplement();

        if ($partiallyImplementedInterfaceTypeResolvers = $this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers()) {
            $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES] = [];
            foreach ($partiallyImplementedInterfaceTypeResolvers as $interfaceTypeResolver) {
                $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES][] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
                $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
            }
        }

        return $schemaDefinition;
    }
}
