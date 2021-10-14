<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class ObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected ObjectTypeResolverInterface $objectTypeResolver,
    ) {
        parent::__construct($objectTypeResolver);
    }

    public function getType(): string
    {
        return SchemaDefinition::TYPE_OBJECT;
    }

    public function getSchemaDefinition(): array
    {
        return array_merge(
            parent::getSchemaDefinition(),
            $this->getObjectTypeSchemaDefinition(false)
        );
    }

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    protected function getObjectTypeSchemaDefinition(bool $useGlobal): array
    {
        // Add the directives (non-global)
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->objectTypeResolver->getSchemaDirectiveResolvers($useGlobal);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
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

        $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES] = [];
        foreach ($this->objectTypeResolver->getImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
            $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES][] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
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
        if ($objectTypeFieldResolver->skipAddingToSchemaDefinition($this->objectTypeResolver, $fieldName)) {
            return;
        }

        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this->objectTypeResolver, $fieldName, []);

        // Extract the typeResolvers
        $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        $this->accessedTypeAndDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
        $this->replaceTypeResolverWithTypeType($fieldSchemaDefinition);

        foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
            $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
            $this->replaceTypeResolverWithTypeType($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
        }

        // Split the results into "fields" and "connections"
        $isConnection = $fieldTypeResolver instanceof RelationalTypeResolverInterface;
        $entry = $isConnection ?
            SchemaDefinition::CONNECTIONS :
            SchemaDefinition::FIELDS;
        $objectTypeSchemaDefinition[$entry][$fieldName] = $fieldSchemaDefinition;
    }
}
