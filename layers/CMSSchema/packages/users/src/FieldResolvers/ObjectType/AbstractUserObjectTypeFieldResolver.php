<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserPaginationInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UsersFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSortInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class AbstractUserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?UsersFilterInputObjectTypeResolver $usersFilterInputObjectTypeResolver = null;
    private ?UserPaginationInputObjectTypeResolver $userPaginationInputObjectTypeResolver = null;
    private ?UserSortInputObjectTypeResolver $userSortInputObjectTypeResolver = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setUsersFilterInputObjectTypeResolver(UsersFilterInputObjectTypeResolver $usersFilterInputObjectTypeResolver): void
    {
        $this->usersFilterInputObjectTypeResolver = $usersFilterInputObjectTypeResolver;
    }
    final protected function getUsersFilterInputObjectTypeResolver(): UsersFilterInputObjectTypeResolver
    {
        return $this->usersFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(UsersFilterInputObjectTypeResolver::class);
    }
    final public function setUserPaginationInputObjectTypeResolver(UserPaginationInputObjectTypeResolver $userPaginationInputObjectTypeResolver): void
    {
        $this->userPaginationInputObjectTypeResolver = $userPaginationInputObjectTypeResolver;
    }
    final protected function getUserPaginationInputObjectTypeResolver(): UserPaginationInputObjectTypeResolver
    {
        return $this->userPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserPaginationInputObjectTypeResolver::class);
    }
    final public function setUserSortInputObjectTypeResolver(UserSortInputObjectTypeResolver $userSortInputObjectTypeResolver): void
    {
        $this->userSortInputObjectTypeResolver = $userSortInputObjectTypeResolver;
    }
    final protected function getUserSortInputObjectTypeResolver(): UserSortInputObjectTypeResolver
    {
        return $this->userSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserSortInputObjectTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'users',
            'userCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'users' => $this->getUserObjectTypeResolver(),
            'userCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'userCount' => SchemaTypeModifiers::NON_NULLABLE,
            'users' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'users' => $this->__('Users', 'pop-users'),
            'userCount' => $this->__('Number of users', 'pop-users'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'users' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getUsersFilterInputObjectTypeResolver(),
                    'pagination' => $this->getUserPaginationInputObjectTypeResolver(),
                    'sort' => $this->getUserSortInputObjectTypeResolver(),
                ]
            ),
            'userCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getUsersFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'users':
                return $this->getUserTypeAPI()->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'userCount':
                return $this->getUserTypeAPI()->getUserCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
