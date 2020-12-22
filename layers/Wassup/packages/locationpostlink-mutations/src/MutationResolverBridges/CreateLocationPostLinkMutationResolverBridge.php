<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\CreateLocationPostLinkMutationResolver;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\AbstractCreateUpdateLocationPostMutationResolverBridge;

class CreateLocationPostLinkMutationResolverBridge extends AbstractCreateUpdateLocationPostMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateLocationPostLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
