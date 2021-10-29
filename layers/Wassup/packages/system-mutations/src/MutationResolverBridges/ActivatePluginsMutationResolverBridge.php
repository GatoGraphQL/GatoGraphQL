<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\ActivatePluginsMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class ActivatePluginsMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected ?ActivatePluginsMutationResolver $activatePluginsMutationResolver = null;

    #[Required]
    final public function autowireActivatePluginsMutationResolverBridge(
        ActivatePluginsMutationResolver $activatePluginsMutationResolver,
    ): void {
        $this->activatePluginsMutationResolver = $activatePluginsMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getActivatePluginsMutationResolver();
    }

    public function getSuccessString(string | int $result_ids): ?string
    {
        return $result_ids ? sprintf(
            $this->getTranslationAPI()->__('Successfully activated plugins: %s.', 'pop-system-wp'),
            implode($this->getTranslationAPI()->__(', ', 'pop-system-wp'), (array) $result_ids)
        ) : $this->getTranslationAPI()->__('There were no plugins to activate.', 'pop-system-wp');
    }
}
