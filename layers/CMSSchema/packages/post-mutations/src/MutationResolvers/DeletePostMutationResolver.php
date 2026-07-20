<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractDeleteCustomPostMutationResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class DeletePostMutationResolver extends AbstractDeleteCustomPostMutationResolver
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }

    protected function getTargetCustomPostType(): ?string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
}
