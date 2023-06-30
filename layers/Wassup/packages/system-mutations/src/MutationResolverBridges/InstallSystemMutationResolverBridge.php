<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\InstallSystemMutationResolver;

class InstallSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?InstallSystemMutationResolver $installSystemMutationResolver = null;

    final public function setInstallSystemMutationResolver(InstallSystemMutationResolver $installSystemMutationResolver): void
    {
        $this->installSystemMutationResolver = $installSystemMutationResolver;
    }
    final protected function getInstallSystemMutationResolver(): InstallSystemMutationResolver
    {
        if ($this->installSystemMutationResolver === null) {
            /** @var InstallSystemMutationResolver */
            $installSystemMutationResolver = $this->instanceManager->getInstance(InstallSystemMutationResolver::class);
            $this->installSystemMutationResolver = $installSystemMutationResolver;
        }
        return $this->installSystemMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInstallSystemMutationResolver();
    }
    public function getSuccessString(string|int $result_id): ?string
    {
        return $this->__('System action "install" executed successfully.', 'pop-system');
    }
}
