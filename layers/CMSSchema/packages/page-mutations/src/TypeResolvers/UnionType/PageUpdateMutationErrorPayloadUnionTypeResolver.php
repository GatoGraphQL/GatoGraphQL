<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType\PageUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?PageUpdateMutationErrorPayloadUnionTypeDataLoader $pageUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setPageUpdateMutationErrorPayloadUnionTypeDataLoader(PageUpdateMutationErrorPayloadUnionTypeDataLoader $pageUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->pageUpdateMutationErrorPayloadUnionTypeDataLoader = $pageUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getPageUpdateMutationErrorPayloadUnionTypeDataLoader(): PageUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageUpdateMutationErrorPayloadUnionTypeDataLoader */
            $pageUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageUpdateMutationErrorPayloadUnionTypeDataLoader = $pageUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a page (using nested mutations)', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
