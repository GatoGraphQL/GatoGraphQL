<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;

class QueryRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected QueryRootTypeDataLoader $queryRootTypeDataLoader;

    #[Required]
    public function autowireQueryRootObjectTypeResolver(
        RootObjectTypeResolver $rootObjectTypeResolver,
        QueryRootTypeDataLoader $queryRootTypeDataLoader,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->queryRootTypeDataLoader = $queryRootTypeDataLoader;
    }

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

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->queryRootTypeDataLoader;
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
