<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageUpdateMetaMutationErrorPayloadUnionTypeResolver $postUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageUpdateMetaMutationErrorPayloadUnionTypeResolver(): PageUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postUpdateMetaMutationErrorPayloadUnionTypeResolver = $postUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
