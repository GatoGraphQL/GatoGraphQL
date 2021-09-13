<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\RelationalTypeDataLoaders\ObjectType\HighlightTypeDataLoader;

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

    public function getRelationalTypeDataLoaderClass(): string
    {
        return HighlightTypeDataLoader::class;
    }
}
