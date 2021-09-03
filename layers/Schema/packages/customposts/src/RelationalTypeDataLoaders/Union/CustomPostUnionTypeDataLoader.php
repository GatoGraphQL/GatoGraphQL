<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\Union;

use PoP\ComponentModel\RelationalTypeDataLoaders\Union\AbstractUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    protected function getUnionTypeResolverClass(): string
    {
        return CustomPostUnionTypeResolver::class;
    }
}
