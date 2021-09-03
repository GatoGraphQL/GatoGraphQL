<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeDataLoaders\HighlightTypeDataLoader;

class HighlightTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'Highlight';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('A highlighted piece of text, extracted from a post', 'highlights');
    }

    public function getID(object $resultItem): string | int | null
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->getID($resultItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return HighlightTypeDataLoader::class;
    }
}
