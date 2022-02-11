<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuSortInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenuPaginationInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;
    private ?MenuByInputObjectTypeResolver $menuByInputObjectTypeResolver = null;
    private ?RootMenusFilterInputObjectTypeResolver $rootMenusFilterInputObjectTypeResolver = null;
    private ?RootMenuPaginationInputObjectTypeResolver $rootMenuPaginationInputObjectTypeResolver = null;
    private ?MenuSortInputObjectTypeResolver $menuSortInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setMenuObjectTypeResolver(MenuObjectTypeResolver $menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        return $this->menuObjectTypeResolver ??= $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    final public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }
    final public function setMenuByInputObjectTypeResolver(MenuByInputObjectTypeResolver $menuByInputObjectTypeResolver): void
    {
        $this->menuByInputObjectTypeResolver = $menuByInputObjectTypeResolver;
    }
    final protected function getMenuByInputObjectTypeResolver(): MenuByInputObjectTypeResolver
    {
        return $this->menuByInputObjectTypeResolver ??= $this->instanceManager->getInstance(MenuByInputObjectTypeResolver::class);
    }
    final public function setRootMenusFilterInputObjectTypeResolver(RootMenusFilterInputObjectTypeResolver $rootMenusFilterInputObjectTypeResolver): void
    {
        $this->rootMenusFilterInputObjectTypeResolver = $rootMenusFilterInputObjectTypeResolver;
    }
    final protected function getRootMenusFilterInputObjectTypeResolver(): RootMenusFilterInputObjectTypeResolver
    {
        return $this->rootMenusFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootMenusFilterInputObjectTypeResolver::class);
    }
    final public function setRootMenuPaginationInputObjectTypeResolver(RootMenuPaginationInputObjectTypeResolver $rootMenuPaginationInputObjectTypeResolver): void
    {
        $this->rootMenuPaginationInputObjectTypeResolver = $rootMenuPaginationInputObjectTypeResolver;
    }
    final protected function getRootMenuPaginationInputObjectTypeResolver(): RootMenuPaginationInputObjectTypeResolver
    {
        return $this->rootMenuPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootMenuPaginationInputObjectTypeResolver::class);
    }
    final public function setMenuSortInputObjectTypeResolver(MenuSortInputObjectTypeResolver $menuSortInputObjectTypeResolver): void
    {
        $this->menuSortInputObjectTypeResolver = $menuSortInputObjectTypeResolver;
    }
    final protected function getMenuSortInputObjectTypeResolver(): MenuSortInputObjectTypeResolver
    {
        return $this->menuSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(MenuSortInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'menu',
            'menus',
            'menuCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'menu' => $this->__('Get a menu', 'menus'),
            'menus' => $this->__('Get all menus', 'menus'),
            'menuCount' => $this->__('Count the number of menus', 'menus'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'menu' => $this->getMenuObjectTypeResolver(),
            'menus' => $this->getMenuObjectTypeResolver(),
            'menuCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'menus' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'menuCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'menu' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getMenuByInputObjectTypeResolver(),
                ]
            ),
            'menus' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMenusFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootMenuPaginationInputObjectTypeResolver(),
                    'sort' => $this->getMenuSortInputObjectTypeResolver(),
                ]
            ),
            'menuCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMenusFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['menu' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
        switch ($fieldName) {
            case 'menu':
                $by = $fieldArgs['by'];
                if (isset($by->id)) {
                    // Validate the ID exists
                    $menuID = $by->id;
                    if ($this->getMenuTypeAPI()->getMenu($menuID) !== null) {
                        return $menuID;
                    }
                }
                return null;
        }

        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'menus':
                return $this->getMenuTypeAPI()->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'menuCount':
                return $this->getMenuTypeAPI()->getMenuCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
