<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\Hooks;

use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostTagTypeMutationAPIInterface $postTagTypeMutationAPI = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

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
    final public function setPostTagTypeMutationAPI(PostTagTypeMutationAPIInterface $postTagTypeMutationAPI): void
    {
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
    }
    final protected function getPostTagTypeMutationAPI(): PostTagTypeMutationAPIInterface
    {
        if ($this->postTagTypeMutationAPI === null) {
            /** @var PostTagTypeMutationAPIInterface */
            $postTagTypeMutationAPI = $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
            $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
        }
        return $this->postTagTypeMutationAPI;
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

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
}
