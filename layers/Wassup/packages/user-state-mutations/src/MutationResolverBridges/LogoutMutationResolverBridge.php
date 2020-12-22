<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LogoutMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return LogoutMutationResolver::class;
    }

    public function getFormData(): array
    {
        return [];
    }
}

