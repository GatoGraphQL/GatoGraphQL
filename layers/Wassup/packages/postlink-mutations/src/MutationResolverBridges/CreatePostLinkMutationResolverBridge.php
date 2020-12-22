<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreatePostLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
