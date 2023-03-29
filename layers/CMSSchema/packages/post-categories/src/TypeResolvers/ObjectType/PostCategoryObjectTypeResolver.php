<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryObjectTypeDataLoader;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    private ?PostCategoryObjectTypeDataLoader $postCategoryObjectTypeDataLoader = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryObjectTypeDataLoader(PostCategoryObjectTypeDataLoader $postCategoryObjectTypeDataLoader): void
    {
        $this->postCategoryObjectTypeDataLoader = $postCategoryObjectTypeDataLoader;
    }
    final protected function getPostCategoryObjectTypeDataLoader(): PostCategoryObjectTypeDataLoader
    {
        /** @var PostCategoryObjectTypeDataLoader */
        return $this->postCategoryObjectTypeDataLoader ??= $this->instanceManager->getInstance(PostCategoryObjectTypeDataLoader::class);
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
        return $this->getPostCategoryObjectTypeDataLoader();
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
}
