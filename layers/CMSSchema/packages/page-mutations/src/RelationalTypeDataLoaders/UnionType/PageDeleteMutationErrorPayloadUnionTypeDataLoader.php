<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageDeleteMutationErrorPayloadUnionTypeResolver $pageDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageDeleteMutationErrorPayloadUnionTypeResolver(): PageDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageDeleteMutationErrorPayloadUnionTypeResolver */
            $pageDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->pageDeleteMutationErrorPayloadUnionTypeResolver = $pageDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
