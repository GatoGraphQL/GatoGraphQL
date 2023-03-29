<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\RelationalTypeDataLoaders\ObjectType\HighlightObjectTypeDataLoader;

class HighlightObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?HighlightObjectTypeDataLoader $highlightObjectTypeDataLoader = null;
    
    final public function setHighlightObjectTypeDataLoader(HighlightObjectTypeDataLoader $highlightObjectTypeDataLoader): void
    {
        $this->highlightObjectTypeDataLoader = $highlightObjectTypeDataLoader;
    }
    final protected function getHighlightObjectTypeDataLoader(): HighlightObjectTypeDataLoader
    {
        /** @var HighlightObjectTypeDataLoader */
        return $this->highlightObjectTypeDataLoader ??= $this->instanceManager->getInstance(HighlightObjectTypeDataLoader::class);
    }
    
    public function getTypeName(): string
    {
        return 'Highlight';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('A highlighted piece of text, extracted from a post', 'highlights');
    }

    public function getID(object $object): string|int|null
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->getID($object);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getHighlightObjectTypeDataLoader();
    }
}
