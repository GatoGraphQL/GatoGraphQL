<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventLinkMutations\MutationResolverBridges;

use PoPSitesWassup\EventLinkMutations\MutationResolvers\UpdateEventLinkMutationResolver;

class UpdateEventLinkMutationResolverBridge extends AbstractCreateUpdateEventLinkMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdateEventLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
