<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
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

        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaInterfaceTypeFieldResolvers = $this->interfaceTypeResolver->getExecutableInterfaceTypeFieldResolversByField();
        foreach ($schemaInterfaceTypeFieldResolvers as $fieldName => $interfaceTypeFieldResolver) {
            $this->addFieldSchemaDefinition($schemaDefinition, $interfaceTypeFieldResolver, $fieldName);
        }

        if ($partiallyImplementedInterfaceTypeResolvers = $this->interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers()) {
            $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES] = [];
            foreach ($partiallyImplementedInterfaceTypeResolvers as $interfaceTypeResolver) {
                $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES][] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
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
        $this->replaceTypeResolverWithTypeType($fieldSchemaDefinition);

        foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
            $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
            $this->replaceTypeResolverWithTypeType($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
        }

        $interfaceTypeSchemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
    }
}
