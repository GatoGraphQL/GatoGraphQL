<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\TypeDataLoaders\MenuItemTypeDataLoader;

class MenuItemTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuItem';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Items (links, pages, etc) added to a menu', 'menus');
    }

    public function getID(object $resultItem): string | int
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        $menuItem = $resultItem;
        return $menuTypeAPI->getMenuItemId($menuItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return MenuItemTypeDataLoader::class;
    }
}
