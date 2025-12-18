<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType\RootMyMenusFilterInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByOneofInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuSortInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenuPaginationInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

class UserStateRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?MenuTypeAPIInterface $menuTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?MenuByOneofInputObjectTypeResolver $menuByOneofInputObjectTypeResolver = null;
    private ?RootMyMenusFilterInputObjectTypeResolver $rootMyMenusFilterInputObjectTypeResolver = null;
    private ?RootMenuPaginationInputObjectTypeResolver $rootMenuPaginationInputObjectTypeResolver = null;
    private ?MenuSortInputObjectTypeResolver $menuSortInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        if ($this->menuTypeAPI === null) {
            /** @var MenuTypeAPIInterface */
            $menuTypeAPI = $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
            $this->menuTypeAPI = $menuTypeAPI;
        }
        return $this->menuTypeAPI;
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
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        if ($this->menuObjectTypeResolver === null) {
            /** @var MenuObjectTypeResolver */
            $menuObjectTypeResolver = $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
            $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        }
        return $this->menuObjectTypeResolver;
    }
    final protected function getMenuByOneofInputObjectTypeResolver(): MenuByOneofInputObjectTypeResolver
    {
        if ($this->menuByOneofInputObjectTypeResolver === null) {
            /** @var MenuByOneofInputObjectTypeResolver */
            $menuByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MenuByOneofInputObjectTypeResolver::class);
            $this->menuByOneofInputObjectTypeResolver = $menuByOneofInputObjectTypeResolver;
        }
        return $this->menuByOneofInputObjectTypeResolver;
    }
    final protected function getRootMyMenusFilterInputObjectTypeResolver(): RootMyMenusFilterInputObjectTypeResolver
    {
        if ($this->rootMyMenusFilterInputObjectTypeResolver === null) {
            /** @var RootMyMenusFilterInputObjectTypeResolver */
            $rootMyMenusFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMyMenusFilterInputObjectTypeResolver::class);
            $this->rootMyMenusFilterInputObjectTypeResolver = $rootMyMenusFilterInputObjectTypeResolver;
        }
        return $this->rootMyMenusFilterInputObjectTypeResolver;
    }
    final protected function getRootMenuPaginationInputObjectTypeResolver(): RootMenuPaginationInputObjectTypeResolver
    {
        if ($this->rootMenuPaginationInputObjectTypeResolver === null) {
            /** @var RootMenuPaginationInputObjectTypeResolver */
            $rootMenuPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(RootMenuPaginationInputObjectTypeResolver::class);
            $this->rootMenuPaginationInputObjectTypeResolver = $rootMenuPaginationInputObjectTypeResolver;
        }
        return $this->rootMenuPaginationInputObjectTypeResolver;
    }
    final protected function getMenuSortInputObjectTypeResolver(): MenuSortInputObjectTypeResolver
    {
        if ($this->menuSortInputObjectTypeResolver === null) {
            /** @var MenuSortInputObjectTypeResolver */
            $menuSortInputObjectTypeResolver = $this->instanceManager->getInstance(MenuSortInputObjectTypeResolver::class);
            $this->menuSortInputObjectTypeResolver = $menuSortInputObjectTypeResolver;
        }
        return $this->menuSortInputObjectTypeResolver;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'myMenu',
            'myMenuCount',
            'myMenus',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myMenuCount'
                => $this->getIntScalarTypeResolver(),
            'myMenus',
            'myMenu'
                => $this->getMenuObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'myMenuCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'myMenus'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myMenu' => $this->__('Menu item by the logged-in user on the site with a specific ID', 'menu-mutations'),
            'myMenuCount' => $this->__('Number of menus by the logged-in user on the site', 'menu-mutations'),
            'myMenus' => $this->__('Menu items by the logged-in user on the site', 'menu-mutations'),
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
            'myMenu' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getMenuByOneofInputObjectTypeResolver(),
                ]
            ),
            'myMenus' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyMenusFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootMenuPaginationInputObjectTypeResolver(),
                    'sort' => $this->getMenuSortInputObjectTypeResolver(),
                ]
            ),
            'myMenuCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyMenusFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['myMenu' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            [
                'author-ids' => [App::getState('current-user-id')],
            ]
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myMenuCount':
                return $this->getMenuTypeAPI()->getMenuCount($query);
            case 'myMenus':
                return $this->getMenuTypeAPI()->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myMenu':
                if ($menus = $this->getMenuTypeAPI()->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $menus[0];
                }
                return null;
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

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
