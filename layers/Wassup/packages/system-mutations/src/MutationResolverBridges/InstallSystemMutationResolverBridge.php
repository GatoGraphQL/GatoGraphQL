<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\InstallSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class InstallSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected InstallSystemMutationResolver $InstallSystemMutationResolver,
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
        return $this->InstallSystemMutationResolver;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "install" executed successfully.', 'pop-system');
    }
}
