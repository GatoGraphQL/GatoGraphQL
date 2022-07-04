<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP_Module_Processor_LoginTextFormInputs;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\ResetLostPasswordMutationResolver;

class ResetLostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver = null;

    final public function setResetLostPasswordMutationResolver(ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver): void
    {
        $this->resetLostPasswordMutationResolver = $resetLostPasswordMutationResolver;
    }
    final protected function getResetLostPasswordMutationResolver(): ResetLostPasswordMutationResolver
    {
        return $this->resetLostPasswordMutationResolver ??= $this->instanceManager->getInstance(ResetLostPasswordMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getResetLostPasswordMutationResolver();
    }

    public function appendMutationDataToFieldDataProvider(\PoP\ComponentModel\Mutation\FieldDataProviderInterface $fieldDataProvider): void
    {
        $fieldDataProvider->add(MutationInputProperties::CODE, trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE])));
        $fieldDataProvider->add(MutationInputProperties::PASSWORD, trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])));
        $fieldDataProvider->add(MutationInputProperties::REPEAT_PASSWORD, trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])));
    }
}
