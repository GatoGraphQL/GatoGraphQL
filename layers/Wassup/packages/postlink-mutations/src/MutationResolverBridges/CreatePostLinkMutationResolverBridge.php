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
        /** @var CreatePostLinkMutationResolver */
        return $this->createPostLinkMutationResolver ??= $this->instanceManager->getInstance(CreatePostLinkMutationResolver::class);
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
