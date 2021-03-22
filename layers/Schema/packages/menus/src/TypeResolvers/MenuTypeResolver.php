<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers;

use PoPSchema\Menus\TypeDataLoaders\MenuTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class MenuTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Menu';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $resultItem): mixed
    {
        $cmsmenusresolver = \PoPSchema\Menus\ObjectPropertyResolverFactory::getInstance();
        $menu = $resultItem;
        return $cmsmenusresolver->getMenuTermId($menu);
    }

    public function getTypeDataLoaderClass(): string
    {
        return MenuTypeDataLoader::class;
    }
}
