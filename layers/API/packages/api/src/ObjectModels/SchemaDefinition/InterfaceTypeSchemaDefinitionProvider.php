<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\TypeKinds;

class InterfaceTypeSchemaDefinitionProvider extends AbstractNamedTypeSchemaDefinitionProvider
{
    public function __construct(
        protected InterfaceTypeResolverInterface $interfaceTypeResolver,
    ) {
        parent::__construct($interfaceTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::INTERFACE;
    }

    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addPossibleTypeSchemaDefinitions($schemaDefinition);
        $this->addFieldSchemaDefinitions($schemaDefinition);
        $this->addInterfaceSchemaDefinitions($schemaDefinition);

        return $schemaDefinition;
    }

    /**
     * Watch out! The POSSIBLE_TYPES are injected in SchemaDefinitionService,
     * so that only typeResolvers accessible from the Root are analyzed,
     * and not necessarily all of them (as they appear in the TypeRegistry)
     *
     * For instance, QueryRoot with nested mutations enabled must be skipped,
     * yet it would be retrieved if reading the types from the typeRegistry
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addPossibleTypeSchemaDefinitions(array &$schemaDefinition): void
    {
        // Initialize it here, but it will be filled in SchemaDefinitionService
        $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES] = [];
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addFieldSchemaDefinitions(array &$schemaDefinition): void
    {
        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaInterfaceTypeFieldResolvers = $this->interfaceTypeResolver->getExecutableInterfaceTypeFieldResolversByField();
        foreach ($schemaInterfaceTypeFieldResolvers as $fieldName => $interfaceTypeFieldResolver) {
            // Fields may not be directly visible in the schema
            if ($interfaceTypeFieldResolver->skipExposingFieldInSchema($fieldName)) {
                continue;
            }

            $fieldSchemaDefinition = $interfaceTypeFieldResolver->getFieldSchemaDefinition($fieldName);

            // Extract the typeResolvers
            /** @var TypeResolverInterface */
            $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndFieldDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition);

            foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
                /** @var TypeResolverInterface */
                $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
                $this->accessedTypeAndFieldDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
                SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
            }

            $schemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
        }
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addInterfaceSchemaDefinitions(array &$schemaDefinition): void
    {
        $implementedInterfaceTypeResolvers = $this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers();
        if ($implementedInterfaceTypeResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        foreach ($implementedInterfaceTypeResolvers as $interfaceTypeResolver) {
            $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndFieldDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
        }
    }
}
