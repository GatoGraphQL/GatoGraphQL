<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType\PageDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageDeleteMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMutationErrorPayloadUnionTypeResolver
{
    private ?PageDeleteMutationErrorPayloadUnionTypeDataLoader $pageDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageDeleteMutationErrorPayloadUnionTypeDataLoader(): PageDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->pageDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageDeleteMutationErrorPayloadUnionTypeDataLoader */
            $pageDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->pageDeleteMutationErrorPayloadUnionTypeDataLoader = $pageDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->pageDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a page', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
