<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserPaginationInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSortInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UsersFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractUserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?UsersFilterInputObjectTypeResolver $usersFilterInputObjectTypeResolver = null;
    private ?UserPaginationInputObjectTypeResolver $userPaginationInputObjectTypeResolver = null;
    private ?UserSortInputObjectTypeResolver $userSortInputObjectTypeResolver = null;

    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final protected function getUsersFilterInputObjectTypeResolver(): UsersFilterInputObjectTypeResolver
    {
        if ($this->usersFilterInputObjectTypeResolver === null) {
            /** @var UsersFilterInputObjectTypeResolver */
            $usersFilterInputObjectTypeResolver = $this->instanceManager->getInstance(UsersFilterInputObjectTypeResolver::class);
            $this->usersFilterInputObjectTypeResolver = $usersFilterInputObjectTypeResolver;
        }
        return $this->usersFilterInputObjectTypeResolver;
    }
    final protected function getUserPaginationInputObjectTypeResolver(): UserPaginationInputObjectTypeResolver
    {
        if ($this->userPaginationInputObjectTypeResolver === null) {
            /** @var UserPaginationInputObjectTypeResolver */
            $userPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(UserPaginationInputObjectTypeResolver::class);
            $this->userPaginationInputObjectTypeResolver = $userPaginationInputObjectTypeResolver;
        }
        return $this->userPaginationInputObjectTypeResolver;
    }
    final protected function getUserSortInputObjectTypeResolver(): UserSortInputObjectTypeResolver
    {
        if ($this->userSortInputObjectTypeResolver === null) {
            /** @var UserSortInputObjectTypeResolver */
            $userSortInputObjectTypeResolver = $this->instanceManager->getInstance(UserSortInputObjectTypeResolver::class);
            $this->userSortInputObjectTypeResolver = $userSortInputObjectTypeResolver;
        }
        return $this->userSortInputObjectTypeResolver;
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'users':
                return $this->getUserTypeAPI()->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'userCount':
                return $this->getUserTypeAPI()->getUserCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
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
