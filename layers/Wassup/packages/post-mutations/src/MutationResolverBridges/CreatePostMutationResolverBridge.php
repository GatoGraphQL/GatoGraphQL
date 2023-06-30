<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\CreatePostMutationResolver;

class CreatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    private ?CreatePostMutationResolver $createPostMutationResolver = null;

    final public function setCreatePostMutationResolver(CreatePostMutationResolver $createPostMutationResolver): void
    {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }
    final protected function getCreatePostMutationResolver(): CreatePostMutationResolver
    {
        if ($this->createPostMutationResolver === null) {
            /** @var CreatePostMutationResolver */
            $createPostMutationResolver = $this->instanceManager->getInstance(CreatePostMutationResolver::class);
            $this->createPostMutationResolver = $createPostMutationResolver;
        }
        return $this->createPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreatePostMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
