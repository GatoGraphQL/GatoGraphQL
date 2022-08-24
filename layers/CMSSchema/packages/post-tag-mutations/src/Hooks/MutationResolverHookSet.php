<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\Hooks;

use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostTagTypeMutationAPIInterface $postTagTypeMutationAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        /** @var PostTypeAPIInterface */
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    final public function setPostTagTypeMutationAPI(PostTagTypeMutationAPIInterface $postTagTypeMutationAPI): void
    {
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
    }
    final protected function getPostTagTypeMutationAPI(): PostTagTypeMutationAPIInterface
    {
        /** @var PostTagTypeMutationAPIInterface */
        return $this->postTagTypeMutationAPI ??= $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }
}
