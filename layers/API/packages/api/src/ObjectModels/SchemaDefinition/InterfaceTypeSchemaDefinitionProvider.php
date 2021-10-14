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

    public function getType(): string
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
            $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES][] = $objectTypeResolver->getMaybeNamespacedTypeName();
            $this->accessedTypeAndDirectiveResolvers[$objectTypeResolver::class] = $objectTypeResolver;
        }

        if ($partiallyImplementedInterfaceTypeResolvers = $this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers()) {
            $schemaDefinition[SchemaDefinition::INTERFACES] = [];
            foreach ($partiallyImplementedInterfaceTypeResolvers as $interfaceTypeResolver) {
                $schemaDefinition[SchemaDefinition::INTERFACES][] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
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
