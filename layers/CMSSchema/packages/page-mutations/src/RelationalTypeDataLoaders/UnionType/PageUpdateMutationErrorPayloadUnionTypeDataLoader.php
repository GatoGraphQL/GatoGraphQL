<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PageUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PageUpdateMutationErrorPayloadUnionTypeResolver $pageUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setPageUpdateMutationErrorPayloadUnionTypeResolver(PageUpdateMutationErrorPayloadUnionTypeResolver $pageUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->pageUpdateMutationErrorPayloadUnionTypeResolver = $pageUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getPageUpdateMutationErrorPayloadUnionTypeResolver(): PageUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageUpdateMutationErrorPayloadUnionTypeResolver */
            $pageUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->pageUpdateMutationErrorPayloadUnionTypeResolver = $pageUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPageUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
