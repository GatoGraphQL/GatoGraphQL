<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\RelationalTypeDataLoaders\ObjectType\HighlightTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class HighlightObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?HighlightTypeDataLoader $highlightTypeDataLoader = null;
    
    public function setHighlightTypeDataLoader(HighlightTypeDataLoader $highlightTypeDataLoader): void
    {
        $this->highlightTypeDataLoader = $highlightTypeDataLoader;
    }
    protected function getHighlightTypeDataLoader(): HighlightTypeDataLoader
    {
        return $this->highlightTypeDataLoader ??= $this->instanceManager->getInstance(HighlightTypeDataLoader::class);
    }

    //#[Required]
    final public function autowireHighlightObjectTypeResolver(
        HighlightTypeDataLoader $highlightTypeDataLoader,
    ): void {
        $this->highlightTypeDataLoader = $highlightTypeDataLoader;
    }
    
    public function getTypeName(): string
    {
        return 'Highlight';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('A highlighted piece of text, extracted from a post', 'highlights');
    }

    public function getID(object $object): string | int | null
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->getID($object);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getHighlightTypeDataLoader();
    }
}
