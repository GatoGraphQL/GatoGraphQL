<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventMutations\MutationResolverBridges;

use PoPSitesWassup\EventMutations\MutationResolvers\CreateEventMutationResolver;

class CreateEventMutationResolverBridge extends AbstractCreateUpdateEventMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateEventMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
