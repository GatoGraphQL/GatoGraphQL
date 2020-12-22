<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoPSitesWassup\PostMutations\MutationResolvers\CreatePostMutationResolver;

class CreatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreatePostMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
