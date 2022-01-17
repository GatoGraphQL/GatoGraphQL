<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;
use PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    private ?PostCategoryTypeDataLoader $postCategoryTypeDataLoader = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryTypeDataLoader(PostCategoryTypeDataLoader $postCategoryTypeDataLoader): void
    {
        $this->postCategoryTypeDataLoader = $postCategoryTypeDataLoader;
    }
    final protected function getPostCategoryTypeDataLoader(): PostCategoryTypeDataLoader
    {
        return $this->postCategoryTypeDataLoader ??= $this->instanceManager->getInstance(PostCategoryTypeDataLoader::class);
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryTypeDataLoader();
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
}
