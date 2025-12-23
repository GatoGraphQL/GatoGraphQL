<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\TypeResolvers\EnumType\MenuItemTypeEnumTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class MenuItemInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MenuItemTypeEnumTypeResolver $menuItemTypeEnumTypeResolver = null;
    private ?MenuItemInputObjectTypeResolver $menuItemInputObjectTypeResolver = null;

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

    final protected function getMenuItemTypeEnumTypeResolver(): MenuItemTypeEnumTypeResolver
    {
        if ($this->menuItemTypeEnumTypeResolver === null) {
            /** @var MenuItemTypeEnumTypeResolver */
            $menuItemTypeEnumTypeResolver = $this->instanceManager->getInstance(MenuItemTypeEnumTypeResolver::class);
            $this->menuItemTypeEnumTypeResolver = $menuItemTypeEnumTypeResolver;
        }
        return $this->menuItemTypeEnumTypeResolver;
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

    public function getTypeName(): string
    {
        return 'MenuItemInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input for a Menu Item', 'menu-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::LABEL => $this->getStringScalarTypeResolver(),
            MutationInputProperties::TITLE_ATTRIBUTE => $this->getStringScalarTypeResolver(),
            MutationInputProperties::URL => $this->getStringScalarTypeResolver(),
            MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
            MutationInputProperties::CSS_CLASSES => $this->getStringScalarTypeResolver(),
            MutationInputProperties::TARGET => $this->getStringScalarTypeResolver(),
            MutationInputProperties::LINK_RELATIONSHIP => $this->getStringScalarTypeResolver(),
            MutationInputProperties::OBJECT_ID => $this->getIDScalarTypeResolver(),
            MutationInputProperties::ITEM_TYPE => $this->getMenuItemTypeEnumTypeResolver(),
            MutationInputProperties::OBJECT_TYPE => $this->getStringScalarTypeResolver(),
            MutationInputProperties::CHILDREN => $this->getMenuItemInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::LABEL => $this->__('Menu item label', 'menu-mutations'),
            MutationInputProperties::TITLE_ATTRIBUTE => $this->__('Menu item title attribute (attr-title)', 'menu-mutations'),
            MutationInputProperties::URL => $this->__('Menu item URL', 'menu-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('Menu item description', 'menu-mutations'),
            MutationInputProperties::CSS_CLASSES => $this->__('Menu item CSS classes', 'menu-mutations'),
            MutationInputProperties::TARGET => $this->__('Menu item target', 'menu-mutations'),
            MutationInputProperties::LINK_RELATIONSHIP => $this->__('Menu item link relationship (rel/xfn)', 'menu-mutations'),
            MutationInputProperties::OBJECT_ID => $this->__('The ID of the linked object (eg: post ID)', 'menu-mutations'),
            MutationInputProperties::ITEM_TYPE => $this->__('The menu item type', 'menu-mutations'),
            MutationInputProperties::OBJECT_TYPE => $this->__('The type of the linked object (post type or taxonomy)', 'menu-mutations'),
            MutationInputProperties::CHILDREN => $this->__('Child menu items', 'menu-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::CSS_CLASSES,
            MutationInputProperties::CHILDREN
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
