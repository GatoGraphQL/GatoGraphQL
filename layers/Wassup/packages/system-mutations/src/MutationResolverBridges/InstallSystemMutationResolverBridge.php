<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\InstallSystemMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InstallSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected InstallSystemMutationResolver $installSystemMutationResolver;

    #[Required]
    final public function autowireInstallSystemMutationResolverBridge(
        InstallSystemMutationResolver $installSystemMutationResolver,
    ): void {
        $this->installSystemMutationResolver = $installSystemMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->installSystemMutationResolver;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "install" executed successfully.', 'pop-system');
    }
}
