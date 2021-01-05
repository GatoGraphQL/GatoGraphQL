<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class GenerateSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return GenerateSystemMutationResolver::class;
    }
    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    public function getSuccessString($result_id): ?string
    {
        return TranslationAPIFacade::getInstance()->__('System action "generate" executed successfully.', 'pop-system');
    }
}
