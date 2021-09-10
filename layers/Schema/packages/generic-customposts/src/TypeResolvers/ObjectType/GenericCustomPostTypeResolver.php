<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeResolvers\ObjectType;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;
use PoPSchema\GenericCustomPosts\RelationalTypeDataLoaders\ObjectType\GenericCustomPostTypeDataLoader;

class GenericCustomPostTypeResolver extends AbstractCustomPostTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Any custom post, with or without its own type for the schema', 'customposts');
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return GenericCustomPostTypeDataLoader::class;
    }
}
