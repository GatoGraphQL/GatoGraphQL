<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    private ?CreatePostLinkMutationResolver $createPostLinkMutationResolver = null;

    public function setCreatePostLinkMutationResolver(CreatePostLinkMutationResolver $createPostLinkMutationResolver): void
    {
        $this->createPostLinkMutationResolver = $createPostLinkMutationResolver;
    }
    protected function getCreatePostLinkMutationResolver(): CreatePostLinkMutationResolver
    {
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
