<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * Can't inject in constructor because of a circular reference
     */
    protected ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        if ($this->customPostUnionTypeResolver === null) {
            $this->customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
        }
        return $this->customPostUnionTypeResolver;
    }
}
