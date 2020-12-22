<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SystemMutations\MutationResolvers\ActivatePluginsMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class ActivatePluginsMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return ActivatePluginsMutationResolver::class;
    }

    /**
     * @param mixed $result_ids Maybe an int, maybe a string
     */
    public function getSuccessString($result_ids): ?string
    {
        return $result_ids ? sprintf(
            TranslationAPIFacade::getInstance()->__('Successfully activated plugins: %s.', 'pop-system-wp'),
            implode(TranslationAPIFacade::getInstance()->__(', ', 'pop-system-wp'), (array) $result_ids)
        ) : TranslationAPIFacade::getInstance()->__('There were no plugins to activate.', 'pop-system-wp');
    }
}

