<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\Union;

use PoP\ComponentModel\TypeResolvers\Union\AbstractUnionTypeResolver;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\Union\CustomPostUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\Interface\IsCustomPostInterfaceTypeResolver;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostUnion';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return CustomPostUnionTypeDataLoader::class;
    }

    public function getSchemaTypeInterfaceTypeResolverClass(): ?string
    {
        return IsCustomPostInterfaceTypeResolver::class;
    }
}
