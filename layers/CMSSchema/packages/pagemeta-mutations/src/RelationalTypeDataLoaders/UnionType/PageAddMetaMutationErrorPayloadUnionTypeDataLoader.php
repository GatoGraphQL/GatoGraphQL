<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageAddMetaMutationErrorPayloadUnionTypeResolver $postAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeResolver(): PageAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeResolver */
            $postAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postAddMetaMutationErrorPayloadUnionTypeResolver = $postAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
