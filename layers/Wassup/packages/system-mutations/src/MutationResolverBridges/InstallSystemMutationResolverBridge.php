<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\InstallSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class InstallSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return InstallSystemMutationResolver::class;
    }
    public function getSuccessString(mixed $result_id): ?string
    {
        return TranslationAPIFacade::getInstance()->__('System action "install" executed successfully.', 'pop-system');
    }
}
