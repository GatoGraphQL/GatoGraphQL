<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\BuildSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class BuildSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return BuildSystemMutationResolver::class;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return TranslationAPIFacade::getInstance()->__('System action "build" executed successfully.', 'pop-system');
        ;
    }
}
