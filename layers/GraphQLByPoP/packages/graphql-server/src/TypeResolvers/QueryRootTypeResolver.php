<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
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
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Query type, starting from which the query is executed', 'graphql-server');
    }

    public function getID(object $resultItem)
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
            // Field "mutationRoot" is a helper, must not be ported to QueryRoot
            !in_array($fieldName, ['mutationRoot'])
            && $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) === null;
    }
}
