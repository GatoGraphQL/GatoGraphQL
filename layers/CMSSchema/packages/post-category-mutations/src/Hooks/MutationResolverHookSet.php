<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }
    final public function setPostCategoryTypeMutationAPI(PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface): void
    {
        $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
    }
    final protected function getPostCategoryTypeMutationAPI(): PostCategoryTypeMutationAPIInterface
    {
        if ($this->postCategoryTypeMutationAPIInterface === null) {
            /** @var PostCategoryTypeMutationAPIInterface */
            $postCategoryTypeMutationAPIInterface = $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
            $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
        }
        return $this->postCategoryTypeMutationAPIInterface;
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
}
