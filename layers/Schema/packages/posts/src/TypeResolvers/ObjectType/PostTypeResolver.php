<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\ObjectType;

use PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;

class PostTypeResolver extends AbstractCustomPostTypeResolver
{
    public function getTypeName(): string
    {
        return 'Post';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a post', 'posts');
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return PostTypeDataLoader::class;
    }
}
