<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeDataLoaders\HighlightTypeDataLoader;

class HighlightTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Highlight';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('A highlighted piece of text, extracted from a post', 'highlights');
    }

    public function getID(object $resultItem): mixed
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->getID($resultItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return HighlightTypeDataLoader::class;
    }
}
