<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class ObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    /**
     * @var InterfaceTypeResolverInterface[] List of the implemented interfaces, to add this Type to the InterfaceType's POSSIBLE_TYPES
     */
    protected array $implementedInterfaceTypeResolvers = [];

    public function __construct(
        protected ObjectTypeResolverInterface $objectTypeResolver,
    ) {
        parent::__construct($objectTypeResolver);
    }

    /**
     * @return InterfaceTypeResolverInterface[] List of the implemented interfaces, to add this Type to the InterfaceType's POSSIBLE_TYPES
     */
    final public function getImplementedInterfaceTypeResolvers(): array
    {
        return $this->implementedInterfaceTypeResolvers;
    }

    public function getTypeKind(): string
    {
        return TypeKinds::OBJECT;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addDirectiveSchemaDefinitions($schemaDefinition, false);
        $this->addFieldSchemaDefinitions($schemaDefinition, false);
        $this->addInterfaceSchemaDefinitions($schemaDefinition);

        return $schemaDefinition;
    }

    final protected function addDirectiveSchemaDefinitions(array &$schemaDefinition, bool $useGlobal): void
    {
        // Add the directives (non-global)
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->objectTypeResolver->getSchemaDirectiveResolvers($useGlobal);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            // Directives may not be directly visible in the schema
            if ($directiveResolver->skipExposingDirectiveInSchema($this->objectTypeResolver)) {
                continue;
            }
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndDirectiveResolvers[$directiveResolver::class] = $directiveResolver;
            $this->accessedDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class] = $this->objectTypeResolver;
        }
    }

    final protected function addFieldSchemaDefinitions(array &$schemaDefinition, bool $useGlobal): void
    {
        // Add the fields (non-global)
        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaObjectTypeFieldResolvers = $this->objectTypeResolver->getExecutableObjectTypeFieldResolversByField($useGlobal);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            // Fields may not be directly visible in the schema
            if ($objectTypeFieldResolver->skipExposingFieldInSchema($this->objectTypeResolver, $fieldName)) {
                continue;
            }

            // Watch out! We are passing empty $fieldArgs to generate the schema!
            $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this->objectTypeResolver, $fieldName, []);

            // Extract the typeResolvers
            $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition);

            foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
                $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
                $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
                SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
            }
            $schemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
        }
    }

    final protected function addInterfaceSchemaDefinitions(array &$schemaDefinition): void
    {
        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        $this->implementedInterfaceTypeResolvers = $this->objectTypeResolver->getImplementedInterfaceTypeResolvers();
        foreach ($this->implementedInterfaceTypeResolvers as $interfaceTypeResolver) {
            $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
        }
    }
}
