<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageUpdateMetaMutationErrorPayloadUnionTypeResolver $pageUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageUpdateMetaMutationErrorPayloadUnionTypeResolver(): PageUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $pageUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver = $pageUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
