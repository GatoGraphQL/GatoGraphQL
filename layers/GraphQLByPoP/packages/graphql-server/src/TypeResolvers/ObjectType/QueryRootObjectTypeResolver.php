<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootObjectTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

class QueryRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?QueryRootObjectTypeDataLoader $queryRootObjectTypeDataLoader = null;

    final public function setQueryRootObjectTypeDataLoader(QueryRootObjectTypeDataLoader $queryRootObjectTypeDataLoader): void
    {
        $this->queryRootObjectTypeDataLoader = $queryRootObjectTypeDataLoader;
    }
    final protected function getQueryRootObjectTypeDataLoader(): QueryRootObjectTypeDataLoader
    {
        /** @var QueryRootObjectTypeDataLoader */
        return $this->queryRootObjectTypeDataLoader ??= $this->instanceManager->getInstance(QueryRootObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'QueryRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Query type, starting from which the query is executed', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var QueryRoot */
        $queryRoot = $object;
        return $queryRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getQueryRootObjectTypeDataLoader();
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
