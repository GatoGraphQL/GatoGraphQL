<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateOrUpdateMenuInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements CreateMenuInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MenuItemInputObjectTypeResolver $menuItemInputObjectTypeResolver = null;
    private ?MenuItemsByOneofInputObjectTypeResolver $menuItemsByOneofInputObjectTypeResolver = null;
    private ?InputTypeResolverInterface $menuLocationEnumStringScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }

    final protected function getMenuItemInputObjectTypeResolver(): MenuItemInputObjectTypeResolver
    {
        if ($this->menuItemInputObjectTypeResolver === null) {
            /** @var MenuItemInputObjectTypeResolver */
            $menuItemInputObjectTypeResolver = $this->instanceManager->getInstance(MenuItemInputObjectTypeResolver::class);
            $this->menuItemInputObjectTypeResolver = $menuItemInputObjectTypeResolver;
        }
        return $this->menuItemInputObjectTypeResolver;
    }

    final protected function getMenuItemsByOneofInputObjectTypeResolver(): MenuItemsByOneofInputObjectTypeResolver
    {
        if ($this->menuItemsByOneofInputObjectTypeResolver === null) {
            /** @var MenuItemsByOneofInputObjectTypeResolver */
            $menuItemsByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MenuItemsByOneofInputObjectTypeResolver::class);
            $this->menuItemsByOneofInputObjectTypeResolver = $menuItemsByOneofInputObjectTypeResolver;
        }
        return $this->menuItemsByOneofInputObjectTypeResolver;
    }

    final protected function getMenuLocationEnumStringScalarTypeResolver(): InputTypeResolverInterface
    {
        if ($this->menuLocationEnumStringScalarTypeResolver === null) {
            /** @var InputTypeResolverInterface */
            $menuLocationEnumStringScalarTypeResolver = $this->instanceManager->getInstance('PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver');
            $this->menuLocationEnumStringScalarTypeResolver = $menuLocationEnumStringScalarTypeResolver;
        }
        return $this->menuLocationEnumStringScalarTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        $inputFieldNameTypeResolvers = array_merge(
            $this->addMenuInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::ITEMS_BY => $this->getMenuItemsByOneofInputObjectTypeResolver(),
                MutationInputProperties::LOCATIONS => $this->getMenuLocationEnumStringScalarTypeResolver(),
            ]
        );

        // Inject custom post ID, etc
        $inputFieldNameTypeResolvers = App::applyFilters(
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $inputFieldNameTypeResolvers,
            $this,
        );

        return $inputFieldNameTypeResolvers;
    }

    abstract protected function addMenuInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        $inputFieldDescription = match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('Menu item ID', 'menu-mutations'),
            MutationInputProperties::NAME => $this->__('Menu name', 'menu-mutations'),
            MutationInputProperties::SLUG => $this->__('Menu slug', 'menu-mutations'),
            MutationInputProperties::ITEMS_BY => $this->__('Menu items', 'menu-mutations'),
            MutationInputProperties::LOCATIONS => $this->__('Menu locations to assign the menu to', 'menu-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldDescription = App::applyFilters(
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION,
            $inputFieldDescription,
            $inputFieldName,
            $this,
        );

        return $inputFieldDescription;
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        $inputFieldTypeModifiers = match ($inputFieldName) {
            MutationInputProperties::ID => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::LOCATIONS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldTypeModifiers = App::applyFilters(
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            $inputFieldTypeModifiers,
            $inputFieldName,
            $this,
        );

        return $inputFieldTypeModifiers;
    }
}
