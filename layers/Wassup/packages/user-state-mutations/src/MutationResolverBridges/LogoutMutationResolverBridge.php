<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LogoutMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected LogoutMutationResolver $LogoutMutationResolver,
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
        return $this->LogoutMutationResolver;
    }

    public function getFormData(): array
    {
        return [];
    }
}
