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
        if ($this->logoutUserMutationResolver === null) {
            /** @var LogoutUserMutationResolver */
            $logoutUserMutationResolver = $this->instanceManager->getInstance(LogoutUserMutationResolver::class);
            $this->logoutUserMutationResolver = $logoutUserMutationResolver;
        }
        return $this->logoutUserMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getLogoutUserMutationResolver();
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
    }
}
