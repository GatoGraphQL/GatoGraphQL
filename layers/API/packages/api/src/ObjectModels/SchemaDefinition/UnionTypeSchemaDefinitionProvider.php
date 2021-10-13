<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UnionTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected UnionTypeResolverInterface $unionTypeResolver,
    ) {
        parent::__construct($unionTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_UNION;
    }
    
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        // Iterate through the typeResolvers from all the pickers and get their schema definitions
        $schemaDefinition[SchemaDefinition::MEMBER_OBJECT_TYPES] = [];
        foreach ($this->unionTypeResolver->getObjectTypeResolverPickers() as $picker) {
            $pickerObjectTypeResolver = $picker->getObjectTypeResolver();
            $schemaDefinition[SchemaDefinition::MEMBER_OBJECT_TYPES][] = $pickerObjectTypeResolver->getMaybeNamespacedTypeName();
            $this->accessedTypeAndDirectiveResolvers[$pickerObjectTypeResolver::class] = $pickerObjectTypeResolver;
        }

        if (ComponentConfiguration::enableUnionTypeImplementingInterfaceType()) {
            // If it returns an interface as type, add it to the schemaDefinition
            if ($interfaceTypeResolver = $this->unionTypeResolver->getUnionTypeInterfaceTypeResolver()) {
                $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES] = [
                    $interfaceTypeResolver->getMaybeNamespacedTypeName(),
                ];
                $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
            }
        }

        // Add the directives (non-global)
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->unionTypeResolver->getSchemaDirectiveResolvers(false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndDirectiveResolvers[$directiveResolver::class] = $directiveResolver;
        }

        return $schemaDefinition;
    }
}
