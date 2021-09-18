<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;

trait CreateUpdateProfileMutationResolverBridgeTrait
{
    // public function getFormData(): array
    // {
    //     return array_merge(
    //         parent::getFormData(),
    //         $this->getUsercommunitiesFormData()
    //     );
    // }
    protected function getUsercommunitiesFormData()
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        /** @var FormComponentModuleProcessorInterface */
        $moduleProcessor = $moduleprocessor_manager->getProcessor($inputs['communities']);
        $communities = $moduleProcessor->getValue($inputs['communities']);
        return array(
            'communities' => $communities ?? array(),
        );
    }
}
