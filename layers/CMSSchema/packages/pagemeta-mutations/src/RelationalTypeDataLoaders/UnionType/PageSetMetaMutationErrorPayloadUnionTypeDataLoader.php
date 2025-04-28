<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageSetMetaMutationErrorPayloadUnionTypeResolver $postSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageSetMetaMutationErrorPayloadUnionTypeResolver(): PageSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageSetMetaMutationErrorPayloadUnionTypeResolver */
            $postSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postSetMetaMutationErrorPayloadUnionTypeResolver = $postSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
