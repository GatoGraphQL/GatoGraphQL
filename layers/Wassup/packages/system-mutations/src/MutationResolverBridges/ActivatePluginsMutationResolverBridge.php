<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\ActivatePluginsMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class ActivatePluginsMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected ActivatePluginsMutationResolver $ActivatePluginsMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->ActivatePluginsMutationResolver;
    }

    public function getSuccessString(string | int $result_ids): ?string
    {
        return $result_ids ? sprintf(
            $this->translationAPI->__('Successfully activated plugins: %s.', 'pop-system-wp'),
            implode($this->translationAPI->__(', ', 'pop-system-wp'), (array) $result_ids)
        ) : $this->translationAPI->__('There were no plugins to activate.', 'pop-system-wp');
    }
}
