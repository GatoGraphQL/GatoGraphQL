<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventLinkMutations\MutationResolverBridges;

use PoPSitesWassup\EventLinkMutations\MutationResolvers\CreateEventLinkMutationResolver;

class CreateEventLinkMutationResolverBridge extends AbstractCreateUpdateEventLinkMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateEventLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
