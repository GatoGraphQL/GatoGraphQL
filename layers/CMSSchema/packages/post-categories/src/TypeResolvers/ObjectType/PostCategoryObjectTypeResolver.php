<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

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
        /** @var PostCategoryTypeDataLoader */
        return $this->postCategoryTypeDataLoader ??= $this->instanceManager->getInstance(PostCategoryTypeDataLoader::class);
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        /** @var PostCategoryTypeAPIInterface */
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Representation of a category, added to a post (taxonomy: "%s")', 'post-categories'),
            $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName()
        );
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
