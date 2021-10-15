<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class InterfaceTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    protected TypeRegistryInterface $typeRegistry;

    public function __construct(
        protected InterfaceTypeResolverInterface $interfaceTypeResolver,
    ) {
        parent::__construct($interfaceTypeResolver);
        $this->typeRegistry = TypeRegistryFacade::getInstance();
    }

    public function getTypeKind(): string
    {
        return TypeKinds::INTERFACE;
    }

    /**
     * Watch out! The POSSIBLE_TYPES are injected in SchemaDefinitionService,
     * so that only existing typeResolvers are analyzed.
     *
     * For instance, QueryRoot with nested mutations enabled must be skipped,
     * yet it would be retrieved if reading the types from the typeRegistry
     *
     * @return array<string, mixed>
     */
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        // Initialize it here, but it will be filled in SchemaDefinitionService
        $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES] = [];

        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaInterfaceTypeFieldResolvers = $this->interfaceTypeResolver->getExecutableInterfaceTypeFieldResolversByField();
        foreach ($schemaInterfaceTypeFieldResolvers as $fieldName => $interfaceTypeFieldResolver) {
            $this->addFieldSchemaDefinition($schemaDefinition, $interfaceTypeFieldResolver, $fieldName);
        }

        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        foreach ($this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
            $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
        }

        return $schemaDefinition;
    }

    private function addFieldSchemaDefinition(
        array &$interfaceTypeSchemaDefinition,
        InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver,
        string $fieldName
    ): void {
        $fieldSchemaDefinition = $interfaceTypeFieldResolver->getFieldSchemaDefinition($fieldName);

        // Extract the typeResolvers
        $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        $this->accessedTypeAndDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
        SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition);

        foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
            $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
        }

        $interfaceTypeSchemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
    }
}
