<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\UpdateStanceMutationResolver;

class UpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    protected UpdateStanceMutationResolver $updateStanceMutationResolver;
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        UpdateStanceMutationResolver $updateStanceMutationResolver,
    ) {
        $this->updateStanceMutationResolver = $updateStanceMutationResolver;
        }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updateStanceMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
