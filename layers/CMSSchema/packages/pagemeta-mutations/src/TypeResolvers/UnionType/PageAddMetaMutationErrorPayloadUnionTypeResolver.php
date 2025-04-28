<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageAddMetaMutationErrorPayloadUnionTypeDataLoader $pageAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeDataLoader(): PageAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $pageAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageAddMetaMutationErrorPayloadUnionTypeDataLoader = $pageAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a page (using nested mutations)', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
