<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostMutations\MutationResolverBridges;

use PoPSitesWassup\LocationPostMutations\MutationResolvers\CreateLocationPostMutationResolver;

class CreateLocationPostMutationResolverBridge extends AbstractCreateUpdateLocationPostMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateLocationPostMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
