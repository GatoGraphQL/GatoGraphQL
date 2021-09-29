<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\RelationalTypeDataLoaders\ObjectType\HighlightTypeDataLoader;

class HighlightObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected HighlightTypeDataLoader $highlightTypeDataLoader;
    
    #[Required]
    public function autowireHighlightObjectTypeResolver(
        HighlightTypeDataLoader $highlightTypeDataLoader,
    ): void {
        $this->highlightTypeDataLoader = $highlightTypeDataLoader;
    }
    
    public function getTypeName(): string
    {
        return 'Highlight';
    }

    public function getSchemaTypeDescription(): ?string
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
        return $this->highlightTypeDataLoader;
    }
}
