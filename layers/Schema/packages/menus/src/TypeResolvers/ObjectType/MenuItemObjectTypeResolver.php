<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class MenuItemObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected MenuItemTypeDataLoader $menuItemTypeDataLoader;

    #[Required]
    public function autowireMenuItemObjectTypeResolver(
        MenuItemTypeDataLoader $menuItemTypeDataLoader,
    ): void {
        $this->menuItemTypeDataLoader = $menuItemTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MenuItem';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Items (links, pages, etc) added to a menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        /** @var MenuItem */
        $menuItem = $object;
        return $menuItem->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->menuItemTypeDataLoader;
    }
}
