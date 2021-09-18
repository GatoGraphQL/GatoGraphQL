<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LogoutMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return LogoutMutationResolver::class;
    }

    public function getFormData(): array
    {
        return [];
    }
}
