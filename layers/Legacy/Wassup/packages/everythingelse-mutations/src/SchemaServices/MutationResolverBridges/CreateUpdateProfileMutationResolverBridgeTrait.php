<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;
use Symfony\Contracts\Service\Attribute\Required;

trait CreateUpdateProfileMutationResolverBridgeTrait
{
    protected ModuleProcessorManagerInterface $moduleProcessorManager;

    #[Required]
    final public function autowireCreateUpdateProfileMutationResolverBridgeTrait(
        ModuleProcessorManagerInterface $moduleProcessorManager,
    ): void {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }

    // public function getFormData(): array
    // {
    //     return array_merge(
    //         parent::getFormData(),
    //         $this->getUsercommunitiesFormData()
    //     );
    // }
    protected function getUsercommunitiesFormData()
    {
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        /** @var FormComponentModuleProcessorInterface */
        $moduleProcessor = $this->moduleProcessorManager->getProcessor($inputs['communities']);
        $communities = $moduleProcessor->getValue($inputs['communities']);
        return array(
            'communities' => $communities ?? array(),
        );
    }
}
