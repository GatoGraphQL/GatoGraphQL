<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader;

class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'Menu';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        $menu = $object;
        return $menuTypeAPI->getMenuID($menu);
    }

    public function getRelationalTypeDataLoaderClass(): RelationalTypeDataLoaderInterface
    {
        return MenuTypeDataLoader::class;
    }
}
