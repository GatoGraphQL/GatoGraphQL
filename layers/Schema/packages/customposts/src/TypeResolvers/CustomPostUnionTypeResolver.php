<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractUnionTypeResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CustomPosts\TypeDataLoaders\CustomPostUnionTypeDataLoader;

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

    public function getSchemaTypeInterfaceClass(): ?string
    {
        return IsCustomPostFieldInterfaceResolver::class;
    }
}
