<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageDeleteMetaMutationErrorPayloadUnionTypeResolver $postDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageDeleteMetaMutationErrorPayloadUnionTypeResolver(): PageDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postDeleteMetaMutationErrorPayloadUnionTypeResolver = $postDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
