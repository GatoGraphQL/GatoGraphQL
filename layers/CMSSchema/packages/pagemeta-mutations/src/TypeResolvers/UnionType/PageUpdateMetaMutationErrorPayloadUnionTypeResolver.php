<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader $pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a page (using nested mutations)', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
