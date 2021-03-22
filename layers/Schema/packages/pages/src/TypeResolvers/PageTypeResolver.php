<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers;

use PoPSchema\Pages\TypeDataLoaders\PageTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;

class PageTypeResolver extends AbstractCustomPostTypeResolver
{
    public function getTypeName(): string
    {
        return 'Page';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a page', 'pages');
    }

    public function getID(object $resultItem): mixed
    {
        $cmspagesresolver = \PoPSchema\Pages\ObjectPropertyResolverFactory::getInstance();
        $page = $resultItem;
        return $cmspagesresolver->getPageId($page);
    }

    public function getTypeDataLoaderClass(): string
    {
        return PageTypeDataLoader::class;
    }
}
