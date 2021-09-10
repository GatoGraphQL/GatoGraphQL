<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    protected function getUnionTypeResolverClass(): string
    {
        return CustomPostUnionTypeResolver::class;
    }
}
