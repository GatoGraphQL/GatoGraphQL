<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers;

use PoPSchema\Menus\TypeDataLoaders\MenuTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class MenuTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Menu';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $resultItem)
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
