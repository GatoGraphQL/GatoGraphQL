<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Categories\TypeDataLoaders\CategoryTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class CategoryTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Category';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a category, added to a post', 'categories');
    }

    public function getID(object $resultItem): string | int
    {
        $cmscategoriesresolver = \PoPSchema\Categories\ObjectPropertyResolverFactory::getInstance();
        $category = $resultItem;
        return $cmscategoriesresolver->getCategoryID($category);
    }

    public function getTypeDataLoaderClass(): string
    {
        return CategoryTypeDataLoader::class;
    }
}
