<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostMutations\MutationResolverBridges;

use PoPSitesWassup\LocationPostMutations\MutationResolvers\UpdateLocationPostMutationResolver;

class UpdateLocationPostMutationResolverBridge extends AbstractCreateUpdateLocationPostMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdateLocationPostMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
