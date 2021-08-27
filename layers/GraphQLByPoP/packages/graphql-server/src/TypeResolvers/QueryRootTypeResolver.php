<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\QueryRootTypeDataLoader;

class QueryRootTypeResolver extends AbstractUseRootAsSourceForSchemaTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'QueryRoot';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Query type, starting from which the query is executed. Available when \'nested mutations\' is disabled', 'graphql-server');
    }

    public function getID(object $resultItem): string | int | null
    {
        /** @var QueryRoot */
        $queryRoot = $resultItem;
        return $queryRoot->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return QueryRootTypeDataLoader::class;
    }

    protected function isFieldNameConditionSatisfiedForSchema(FieldResolverInterface $fieldResolver, string $fieldName): bool
    {
        return
            // Fields "queryRoot" and "mutationRoot" are helpers, must not be ported to QueryRoot
            !in_array($fieldName, ['queryRoot', 'mutationRoot'])
            && $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) === null;
    }
}
