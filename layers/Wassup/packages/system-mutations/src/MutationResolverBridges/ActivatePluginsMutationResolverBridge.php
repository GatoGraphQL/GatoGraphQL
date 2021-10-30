<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\ActivatePluginsMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class ActivatePluginsMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?ActivatePluginsMutationResolver $activatePluginsMutationResolver = null;

    final public function setActivatePluginsMutationResolver(ActivatePluginsMutationResolver $activatePluginsMutationResolver): void
    {
        $this->activatePluginsMutationResolver = $activatePluginsMutationResolver;
    }
    final protected function getActivatePluginsMutationResolver(): ActivatePluginsMutationResolver
    {
        return $this->activatePluginsMutationResolver ??= $this->instanceManager->getInstance(ActivatePluginsMutationResolver::class);
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
