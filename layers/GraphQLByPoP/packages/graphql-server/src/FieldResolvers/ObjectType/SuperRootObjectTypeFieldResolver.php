<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class SuperRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?RootObjectTypeResolver $rootObjectTypeResolver = null;
    private ?QueryRootObjectTypeResolver $queryRootObjectTypeResolver = null;
    private ?MutationRootObjectTypeResolver $mutationRootObjectTypeResolver = null;

    final public function setRootObjectTypeResolver(RootObjectTypeResolver $rootObjectTypeResolver): void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }
    final protected function getRootObjectTypeResolver(): RootObjectTypeResolver
    {
        /** @var RootObjectTypeResolver */
        return $this->rootObjectTypeResolver ??= $this->instanceManager->getInstance(RootObjectTypeResolver::class);
    }
    final public function setQueryRootObjectTypeResolver(QueryRootObjectTypeResolver $queryRootObjectTypeResolver): void
    {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
    }
    final protected function getQueryRootObjectTypeResolver(): QueryRootObjectTypeResolver
    {
        /** @var QueryRootObjectTypeResolver */
        return $this->queryRootObjectTypeResolver ??= $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
    }
    final public function setMutationRootObjectTypeResolver(MutationRootObjectTypeResolver $mutationRootObjectTypeResolver): void
    {
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }
    final protected function getMutationRootObjectTypeResolver(): MutationRootObjectTypeResolver
    {
        /** @var MutationRootObjectTypeResolver */
        return $this->mutationRootObjectTypeResolver ??= $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            SuperRootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            /**
             * Have 2 fields to retrieve the Root when Nested Mutations
             * are enabled (instead of a single one `_root`) because then
             * we can define Access Control validations on the `query`
             * or `mutation` operation:
             *
             * The corresponding `@validate...` directives will be added
             * to either field `_rootForQueryRoot` or `_rootForMutationRoot`
             * on the SuperRoot object.
             */
            '_rootForQueryRoot',
            '_rootForMutationRoot',

            '_queryRoot',
            '_mutationRoot',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            '_rootForQueryRoot' => $this->__('Get the Root type (as requested by a query operation)', 'engine'),
            '_rootFoMutationRoot' => $this->__('Get the Root type (as requested by a mutation operation)', 'engine'),
            '_queryRoot' => $this->__('Get the Query Root type', 'engine'),
            '_mutationRoot' => $this->__('Get the Mutation Root type', 'engine'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            '_rootForQueryRoot',
            '_rootForMutationRoot'
                => $this->getRootObjectTypeResolver(),
            '_queryRoot'
                => $this->getQueryRootObjectTypeResolver(),
            '_mutationRoot'
                => $this->getMutationRootObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var SuperRoot */
        $superRoot = $object;
        return match ($fieldDataAccessor->getFieldName()) {
            '_rootForQueryRoot',
            '_rootForMutationRoot'
                => Root::ID,
            '_queryRoot'
                => QueryRoot::ID,
            '_mutationRoot'
                => MutationRoot::ID,
            default
                => parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore),
        };
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
