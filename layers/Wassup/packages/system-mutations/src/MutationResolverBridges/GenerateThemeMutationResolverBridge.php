<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateThemeMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class GenerateThemeMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return GenerateThemeMutationResolver::class;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return TranslationAPIFacade::getInstance()->__('System action "generate theme" executed successfully.', 'pop-system');
    }
}
