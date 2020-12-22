<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\UpdateLocationPostLinkMutationResolver;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\AbstractCreateUpdateLocationPostMutationResolverBridge;

class UpdateLocationPostLinkMutationResolverBridge extends AbstractCreateUpdateLocationPostMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdateLocationPostLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
