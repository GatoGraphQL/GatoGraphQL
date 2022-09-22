<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\SuperRoot;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

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
            'root',
            'queryRoot',
            'mutationRoot',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'root' => $this->__('Get the Root type', 'engine'),
            'queryRoot' => $this->__('Get the Query Root type', 'engine'),
            'mutationRoot' => $this->__('Get the Mutation Root type', 'engine'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'root' => $this->getRootObjectTypeResolver(),
            'queryRoot' => $this->getQueryRootObjectTypeResolver(),
            'mutationRoot' => $this->getMutationRootObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
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
            'root' => Root::ID,
            'queryRoot' => QueryRoot::ID,
            'mutationRoot' => MutationRoot::ID,
            default => parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore),
        };
    }
}
