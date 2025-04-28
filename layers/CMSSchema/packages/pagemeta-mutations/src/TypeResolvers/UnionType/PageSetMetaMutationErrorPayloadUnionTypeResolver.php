<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageSetMetaMutationErrorPayloadUnionTypeDataLoader $pageSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageSetMetaMutationErrorPayloadUnionTypeDataLoader(): PageSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $pageSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageSetMetaMutationErrorPayloadUnionTypeDataLoader = $pageSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a page (using nested mutations)', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
