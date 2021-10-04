<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\ActivatePluginsMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class ActivatePluginsMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected ActivatePluginsMutationResolver $activatePluginsMutationResolver;

    #[Required]
    final public function autowireActivatePluginsMutationResolverBridge(
        ActivatePluginsMutationResolver $activatePluginsMutationResolver,
    ): void {
        $this->activatePluginsMutationResolver = $activatePluginsMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->activatePluginsMutationResolver;
    }

    public function getSuccessString(string | int $result_ids): ?string
    {
        return $result_ids ? sprintf(
            $this->translationAPI->__('Successfully activated plugins: %s.', 'pop-system-wp'),
            implode($this->translationAPI->__(', ', 'pop-system-wp'), (array) $result_ids)
        ) : $this->translationAPI->__('There were no plugins to activate.', 'pop-system-wp');
    }
}
