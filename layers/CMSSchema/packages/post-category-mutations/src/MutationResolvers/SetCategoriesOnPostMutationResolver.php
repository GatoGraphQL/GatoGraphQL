<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    private ?PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryTypeMutationAPI(PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI): void
    {
        $this->postCategoryTypeMutationAPI = $postCategoryTypeMutationAPI;
    }
    final protected function getPostCategoryTypeMutationAPI(): PostCategoryTypeMutationAPIInterface
    {
        /** @var PostCategoryTypeMutationAPIInterface */
        return $this->postCategoryTypeMutationAPI ??= $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
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

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }
}
