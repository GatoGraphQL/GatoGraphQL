<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver;

class LogoutUserMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?LogoutUserMutationResolver $logoutUserMutationResolver = null;

    final public function setLogoutUserMutationResolver(LogoutUserMutationResolver $logoutUserMutationResolver): void
    {
        $this->logoutUserMutationResolver = $logoutUserMutationResolver;
    }
    final protected function getLogoutUserMutationResolver(): LogoutUserMutationResolver
    {
        return $this->logoutUserMutationResolver ??= $this->instanceManager->getInstance(LogoutUserMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getLogoutUserMutationResolver();
    }

    public function getFormData(): array
    {
        return [];
    }
}
