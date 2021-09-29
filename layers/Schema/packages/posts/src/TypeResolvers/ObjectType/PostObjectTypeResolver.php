<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    protected PostTypeDataLoader $postTypeDataLoader;

    #[Required]
    public function autowirePostObjectTypeResolver(
        PostTypeDataLoader $postTypeDataLoader,
    ): void {
        $this->postTypeDataLoader = $postTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'Post';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a post', 'posts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->postTypeDataLoader;
    }
}
