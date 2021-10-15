<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UnionTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected UnionTypeResolverInterface $unionTypeResolver,
    ) {
        parent::__construct($unionTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::UNION;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        // Iterate through the typeResolvers from all the pickers and get their schema definitions
        $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES] = [];
        foreach ($this->unionTypeResolver->getObjectTypeResolverPickers() as $picker) {
            $pickerObjectTypeResolver = $picker->getObjectTypeResolver();
            $pickerObjectTypeName = $pickerObjectTypeResolver->getMaybeNamespacedTypeName();
            $pickerObjectTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $pickerObjectTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($pickerObjectTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES][$pickerObjectTypeName] = $pickerObjectTypeSchemaDefinition;
            $this->accessedTypeAndDirectiveResolvers[$pickerObjectTypeResolver::class] = $pickerObjectTypeResolver;
        }

        if (ComponentConfiguration::enableUnionTypeImplementingInterfaceType()) {
            // If it returns an interface as type, add it to the schemaDefinition
            if ($interfaceTypeResolver = $this->unionTypeResolver->getUnionTypeInterfaceTypeResolver()) {
                $schemaDefinition[SchemaDefinition::INTERFACES] = [];
                $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
                $interfaceTypeSchemaDefinition = [
                    SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
                ];
                SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
                $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
                $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
            }
        }

        // Add the directives (non-global)
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->unionTypeResolver->getSchemaDirectiveResolvers(false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndDirectiveResolvers[$directiveResolver::class] = $directiveResolver;
            $this->accessedDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class] = $this->unionTypeResolver;
        }

        return $schemaDefinition;
    }
}
