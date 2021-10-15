<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
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

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaInterfaceTypeFieldResolvers = $this->interfaceTypeResolver->getExecutableInterfaceTypeFieldResolversByField();
        foreach ($schemaInterfaceTypeFieldResolvers as $fieldName => $interfaceTypeFieldResolver) {
            $this->addFieldSchemaDefinition($schemaDefinition, $interfaceTypeFieldResolver, $fieldName);
        }

        // Obtain all the Object Types that implement this interface
        $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES] = [];
        $objectTypeResolvers = $this->typeRegistry->getObjectTypeResolvers();
        foreach ($objectTypeResolvers as $objectTypeResolver) {
            if (!in_array($this->interfaceTypeResolver, $objectTypeResolver->getImplementedInterfaceTypeResolvers())) {
                continue;
            }
            $objectTypeName = $objectTypeResolver->getMaybeNamespacedTypeName();
            $objectTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $objectTypeResolver,
            ];
            $this->replaceTypeResolverWithTypeProperties($objectTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES][$objectTypeName] = $objectTypeSchemaDefinition;
            $this->accessedTypeAndDirectiveResolvers[$objectTypeResolver::class] = $objectTypeResolver;
        }

        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        if ($partiallyImplementedInterfaceTypeResolvers = $this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers()) {
            foreach ($partiallyImplementedInterfaceTypeResolvers as $interfaceTypeResolver) {
                $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
                $interfaceTypeSchemaDefinition = [
                    SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
                ];
                $this->replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
                $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
                $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
            }
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
        $this->replaceTypeResolverWithTypeProperties($fieldSchemaDefinition);

        foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
            $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
            $this->replaceTypeResolverWithTypeProperties($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
        }

        $interfaceTypeSchemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
    }
}
