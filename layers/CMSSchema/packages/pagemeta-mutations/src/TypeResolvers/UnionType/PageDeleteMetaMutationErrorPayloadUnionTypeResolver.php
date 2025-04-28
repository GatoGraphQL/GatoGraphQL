<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader $pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a page (using nested mutations)', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
