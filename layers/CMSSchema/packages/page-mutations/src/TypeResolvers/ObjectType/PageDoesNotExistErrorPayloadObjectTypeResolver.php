<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType\PageDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?PageDoesNotExistErrorPayloadObjectTypeDataLoader $pageDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setPageDoesNotExistErrorPayloadObjectTypeDataLoader(PageDoesNotExistErrorPayloadObjectTypeDataLoader $pageDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->pageDoesNotExistErrorPayloadObjectTypeDataLoader = $pageDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getPageDoesNotExistErrorPayloadObjectTypeDataLoader(): PageDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->pageDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var PageDoesNotExistErrorPayloadObjectTypeDataLoader */
            $pageDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(PageDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->pageDoesNotExistErrorPayloadObjectTypeDataLoader = $pageDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->pageDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested page does not exist"', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
