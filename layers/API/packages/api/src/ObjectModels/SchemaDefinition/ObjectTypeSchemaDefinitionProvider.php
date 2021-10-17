<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
        return array_merge(
            parent::getSchemaDefinition(),
            $this->getObjectTypeSchemaDefinition(false)
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getObjectTypeSchemaDefinition(bool $useGlobal): array
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

        // Add the fields (non-global)
        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaDefinition[SchemaDefinition::CONNECTIONS] = [];
        $schemaObjectTypeFieldResolvers = $this->objectTypeResolver->getExecutableObjectTypeFieldResolversByField($useGlobal);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            $this->addFieldSchemaDefinition($schemaDefinition, $objectTypeFieldResolver, $fieldName);
        }

        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        if ($implementedInterfaceTypeResolvers = $this->objectTypeResolver->getImplementedInterfaceTypeResolvers()) {
            foreach ($implementedInterfaceTypeResolvers as $interfaceTypeResolver) {
                $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
                $interfaceTypeSchemaDefinition = [
                    SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
                ];
                SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
                $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
                $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
            }
            $this->implementedInterfaceTypeResolvers = $implementedInterfaceTypeResolvers;
        }

        return $schemaDefinition;
    }

    private function addFieldSchemaDefinition(
        array &$objectTypeSchemaDefinition,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): void {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($objectTypeFieldResolver->skipExposingFieldInSchema($this->objectTypeResolver, $fieldName)) {
            return;
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

        // Split the results into "fields" and "connections"
        $isConnection = $fieldTypeResolver instanceof RelationalTypeResolverInterface;
        $entry = $isConnection ?
            SchemaDefinition::CONNECTIONS :
            SchemaDefinition::FIELDS;
        $objectTypeSchemaDefinition[$entry][$fieldName] = $fieldSchemaDefinition;
    }
}
