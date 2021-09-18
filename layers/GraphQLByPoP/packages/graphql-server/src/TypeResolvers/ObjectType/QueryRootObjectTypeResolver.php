<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader;

class QueryRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'QueryRoot';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Query type, starting from which the query is executed', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var QueryRoot */
        $queryRoot = $object;
        return $queryRoot->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return QueryRootTypeDataLoader::class;
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
