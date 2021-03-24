<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\SaveDefinitionFileMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class SaveDefinitionFileMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return SaveDefinitionFileMutationResolver::class;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return TranslationAPIFacade::getInstance()->__('System action "save definition file" executed successfully.', 'pop-system');
    }
}
