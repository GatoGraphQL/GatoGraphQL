<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\Object;

use PoPSchema\Posts\RelationalTypeDataLoaders\Object\PostTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\Object\AbstractCustomPostTypeResolver;

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
