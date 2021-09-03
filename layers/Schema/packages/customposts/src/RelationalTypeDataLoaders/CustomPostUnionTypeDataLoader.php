<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders;

use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    protected function getUnionTypeResolverClass(): string
    {
        return CustomPostUnionTypeResolver::class;
    }
}
