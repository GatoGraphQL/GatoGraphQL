<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LogoutMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected LogoutMutationResolver $logoutMutationResolver;

    #[Required]
    public function autowireLogoutMutationResolverBridge(
        LogoutMutationResolver $logoutMutationResolver,
    ): void {
        $this->logoutMutationResolver = $logoutMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->logoutMutationResolver;
    }

    public function getFormData(): array
    {
        return [];
    }
}
