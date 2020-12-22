<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationMutations\MutationResolverBridges;

use PoPSitesWassup\LocationMutations\MutationResolvers\CreateLocationMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class CreateLocationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateLocationMutationResolver::class;
    }

    public function getFormData(): array
    {
        return [];
    }
}
