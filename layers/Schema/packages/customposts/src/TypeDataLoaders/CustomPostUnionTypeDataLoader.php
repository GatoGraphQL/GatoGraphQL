<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    protected function getUnionTypeResolverClass(): string
    {
        return CustomPostUnionTypeResolver::class;
    }
}
