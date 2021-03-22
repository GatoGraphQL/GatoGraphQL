<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Menus\TypeDataLoaders\MenuItemTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

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

    public function getID(object $resultItem): mixed
    {
        $cmsmenusresolver = \PoPSchema\Menus\ObjectPropertyResolverFactory::getInstance();
        $menuItem = $resultItem;
        return $cmsmenusresolver->getMenuItemId($menuItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return MenuItemTypeDataLoader::class;
    }
}
