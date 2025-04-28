<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageAddMetaMutationErrorPayloadUnionTypeResolver $pageAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeResolver(): PageAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeResolver */
            $pageAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageAddMetaMutationErrorPayloadUnionTypeResolver = $pageAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
