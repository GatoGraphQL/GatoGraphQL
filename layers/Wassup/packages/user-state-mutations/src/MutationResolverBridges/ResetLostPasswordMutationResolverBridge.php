<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP_Module_Processor_LoginTextFormInputs;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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

    public function getFormData(): array
    {
        return [
            MutationInputProperties::CODE => trim($this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])),
            MutationInputProperties::PASSWORD => trim($this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])),
            MutationInputProperties::REPEAT_PASSWORD => trim($this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])),
        ];
    }
}
