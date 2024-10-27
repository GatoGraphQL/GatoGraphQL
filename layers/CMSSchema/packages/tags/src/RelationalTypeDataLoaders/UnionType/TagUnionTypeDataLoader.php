<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;

class TagUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?TagUnionTypeResolver $tagUnionTypeResolver = null;

    final protected function getTagUnionTypeResolver(): TagUnionTypeResolver
    {
        if ($this->tagUnionTypeResolver === null) {
            /** @var TagUnionTypeResolver */
            $tagUnionTypeResolver = $this->instanceManager->getInstance(TagUnionTypeResolver::class);
            $this->tagUnionTypeResolver = $tagUnionTypeResolver;
        }
        return $this->tagUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getTagUnionTypeResolver();
    }
}
