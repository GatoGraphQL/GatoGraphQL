<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

abstract class AbstractSchemaElementExtensionsObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;
    
    private ?SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader = null;
    private ?IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver = null;

    final public function setSchemaDefinitionReferenceTypeDataLoader(SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader): void
    {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }
    final protected function getSchemaDefinitionReferenceTypeDataLoader(): SchemaDefinitionReferenceTypeDataLoader
    {
        /** @var SchemaDefinitionReferenceTypeDataLoader */
        return $this->schemaDefinitionReferenceTypeDataLoader ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceTypeDataLoader::class);
    }
    final public function setIdentifiableObjectInterfaceTypeFieldResolver(IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver): void
    {
        $this->identifiableObjectInterfaceTypeFieldResolver = $identifiableObjectInterfaceTypeFieldResolver;
    }
    final protected function getIdentifiableObjectInterfaceTypeFieldResolver(): IdentifiableObjectInterfaceTypeFieldResolver
    {
        /** @var IdentifiableObjectInterfaceTypeFieldResolver */
        return $this->identifiableObjectInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(IdentifiableObjectInterfaceTypeFieldResolver::class);
    }

    /**
     * Introspection names must start with "__".
     * However, when doing so, graphql-js throws an error:
     *
     * @see https://github.com/graphql-java/graphql-java/pull/2221#issuecomment-808044041
     *
     * To avoid it, prepend it with "_", as a temporary solution, until
     * the GraphQL spec and graphql-js deal with the issue.
     *
     * @see https://github.com/graphql/graphql-spec/issues/300#issuecomment-808047303
     */
    final public function getTypeName(): string
    {
        return '_' . $this->getIntrospectionTypeName();
    }
    abstract protected function getIntrospectionTypeName(): string;

    public function getID(object $object): string|int|null
    {
        /** @var SchemaDefinitionReferenceObjectInterface $object */
        return $object->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceTypeDataLoader();
    }

    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    final protected function consolidateAllImplementedInterfaceTypeFieldResolvers(
        array $interfaceTypeFieldResolvers,
    ): array {
        return $this->removeIdentifiableObjectInterfaceTypeFieldResolver(
            parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers),
        );
    }
}
