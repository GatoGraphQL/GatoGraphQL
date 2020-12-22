<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventMutations\MutationResolverBridges;

use PoPSitesWassup\EventMutations\MutationResolvers\UpdateEventMutationResolver;

class UpdateEventMutationResolverBridge extends AbstractCreateUpdateEventMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdateEventMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
