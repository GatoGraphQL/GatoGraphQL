<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    private ?CreatePostLinkMutationResolver $createPostLinkMutationResolver = null;

    final public function setCreatePostLinkMutationResolver(CreatePostLinkMutationResolver $createPostLinkMutationResolver): void
    {
        $this->createPostLinkMutationResolver = $createPostLinkMutationResolver;
    }
    final protected function getCreatePostLinkMutationResolver(): CreatePostLinkMutationResolver
    {
        if ($this->createPostLinkMutationResolver === null) {
            /** @var CreatePostLinkMutationResolver */
            $createPostLinkMutationResolver = $this->instanceManager->getInstance(CreatePostLinkMutationResolver::class);
            $this->createPostLinkMutationResolver = $createPostLinkMutationResolver;
        }
        return $this->createPostLinkMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreatePostLinkMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
