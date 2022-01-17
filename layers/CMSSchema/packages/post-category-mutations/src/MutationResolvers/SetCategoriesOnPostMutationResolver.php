<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    private ?PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI = null;

    final public function setPostCategoryTypeMutationAPI(PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI): void
    {
        $this->postCategoryTypeMutationAPI = $postCategoryTypeMutationAPI;
    }
    final protected function getPostCategoryTypeMutationAPI(): PostCategoryTypeMutationAPIInterface
    {
        return $this->postCategoryTypeMutationAPI ??= $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }
}
