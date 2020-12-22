<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\PostLinkMutations\MutationResolvers\UpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdatePostLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
