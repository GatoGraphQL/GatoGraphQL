<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;
use Symfony\Contracts\Service\Attribute\Required;

trait CreateUpdateProfileMutationResolverBridgeTrait
{
    private ?ModuleProcessorManagerInterface $moduleProcessorManager = null;

    public function setModuleProcessorManager(ModuleProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    protected function getModuleProcessorManager(): ModuleProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ModuleProcessorManagerInterface::class);
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
        $moduleProcessor = $this->getModuleProcessorManager()->getProcessor($inputs['communities']);
        $communities = $moduleProcessor->getValue($inputs['communities']);
        return array(
            'communities' => $communities ?? array(),
        );
    }
}
