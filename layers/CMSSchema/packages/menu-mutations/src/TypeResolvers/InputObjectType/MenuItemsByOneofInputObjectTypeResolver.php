<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;

class MenuItemsByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?MenuItemInputObjectTypeResolver $menuItemInputObjectTypeResolver = null;

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
        return 'MenuItemsByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to select how to provide the menu items', 'menu-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::JSON => $this->getMenuItemInputObjectTypeResolver(),
        ];
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::JSON => $this->__('Input the menu items list', 'menu-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::JSON
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
