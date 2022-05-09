<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

class QueryRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?QueryRootTypeDataLoader $queryRootTypeDataLoader = null;

    final public function setQueryRootTypeDataLoader(QueryRootTypeDataLoader $queryRootTypeDataLoader): void
    {
        $this->queryRootTypeDataLoader = $queryRootTypeDataLoader;
    }
    final protected function getQueryRootTypeDataLoader(): QueryRootTypeDataLoader
    {
        return $this->queryRootTypeDataLoader ??= $this->instanceManager->getInstance(QueryRootTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'QueryRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Query type, starting from which the query is executed', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var QueryRoot */
        $queryRoot = $object;
        return $queryRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getQueryRootTypeDataLoader();
    }

    public function isFieldNameConditionSatisfiedForSchema(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): bool {
        return
            // Fields "queryRoot" and "mutationRoot" are helpers, must not be ported to QueryRoot
            !in_array($fieldName, ['queryRoot', 'mutationRoot'])
            && $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) === null;
    }
}
