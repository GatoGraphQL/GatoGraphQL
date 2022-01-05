<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\Root\App;
use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UnionTypeSchemaDefinitionProvider extends AbstractNamedTypeSchemaDefinitionProvider
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

        $this->addPossibleTypeSchemaDefinitions($schemaDefinition);
        $this->addInterfaceSchemaDefinitions($schemaDefinition);
        $this->addDirectiveSchemaDefinitions($schemaDefinition, false);

        return $schemaDefinition;
    }

    final protected function addPossibleTypeSchemaDefinitions(array &$schemaDefinition): void
    {
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
    }

    final protected function addInterfaceSchemaDefinitions(array &$schemaDefinition): void
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableUnionTypeImplementingInterfaceType()) {
            return;
        }

        // If it returns an interface as type, add it to the schemaDefinition
        $implementedInterfaceTypeResolvers = $this->unionTypeResolver->getUnionTypeInterfaceTypeResolvers();
        if ($implementedInterfaceTypeResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        foreach ($implementedInterfaceTypeResolvers as $implementedInterfaceTypeResolver) {
            $interfaceTypeName = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $implementedInterfaceTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndDirectiveResolvers[$implementedInterfaceTypeResolver::class] = $implementedInterfaceTypeResolver;
        }
    }

    final protected function addDirectiveSchemaDefinitions(array &$schemaDefinition, bool $useGlobal): void
    {
        // Add the directives (non-global)
        $schemaDirectiveResolvers = $this->unionTypeResolver->getSchemaDirectiveResolvers(false);
        if ($schemaDirectiveResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            // Directives may not be directly visible in the schema
            if ($directiveResolver->skipExposingDirectiveInSchema($this->unionTypeResolver)) {
                continue;
            }
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndDirectiveResolvers[$directiveResolver::class] = $directiveResolver;
            $this->accessedDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class] = $this->unionTypeResolver;
        }
    }
}
