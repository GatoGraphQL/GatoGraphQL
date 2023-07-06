<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;

class SetTagsOnPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    private ?PostTagTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeMutationAPI(PostTagTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface): void
    {
        $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
    }
    final protected function getPostTagTypeMutationAPI(): PostTagTypeMutationAPIInterface
    {
        if ($this->postCategoryTypeMutationAPIInterface === null) {
            /** @var PostTagTypeMutationAPIInterface */
            $postCategoryTypeMutationAPIInterface = $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
            $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
        }
        return $this->postCategoryTypeMutationAPIInterface;
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-tag-mutations');
    }
}
