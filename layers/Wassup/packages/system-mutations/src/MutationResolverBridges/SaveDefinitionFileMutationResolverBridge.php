<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\SaveDefinitionFileMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class SaveDefinitionFileMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver;
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver,
    ) {
        $this->saveDefinitionFileMutationResolver = $saveDefinitionFileMutationResolver;
        }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->saveDefinitionFileMutationResolver;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "save definition file" executed successfully.', 'pop-system');
    }
}
