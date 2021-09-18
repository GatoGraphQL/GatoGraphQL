<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoPSitesWassup\SystemMutations\MutationResolvers\InstallSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class InstallSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return InstallSystemMutationResolver::class;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "install" executed successfully.', 'pop-system');
    }
}
