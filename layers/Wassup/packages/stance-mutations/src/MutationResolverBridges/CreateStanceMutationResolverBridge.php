<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateStanceMutationResolver;

class CreateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected CreateStanceMutationResolver $CreateStanceMutationResolver,
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
        return $this->CreateStanceMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
