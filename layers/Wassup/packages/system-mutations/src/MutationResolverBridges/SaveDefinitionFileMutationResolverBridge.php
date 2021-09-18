<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\SaveDefinitionFileMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class SaveDefinitionFileMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function __construct(
        protected \PoP\Hooks\HooksAPIInterface $hooksAPI,
        protected \PoP\Translation\TranslationAPIInterface $translationAPI,
        protected \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        protected \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
    ) {
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->SaveDefinitionFileMutationResolver;
        protected SaveDefinitionFileMutationResolve $SaveDefinitionFileMutationResolver,
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "save definition file" executed successfully.', 'pop-system');
    }
}
