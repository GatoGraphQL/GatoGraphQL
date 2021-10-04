<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\ResetLostPasswordMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class ResetLostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver;

    #[Required]
    final public function autowireResetLostPasswordMutationResolverBridge(
        ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver,
    ): void {
        $this->resetLostPasswordMutationResolver = $resetLostPasswordMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->resetLostPasswordMutationResolver;
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::CODE => trim($this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])),
            MutationInputProperties::PASSWORD => trim($this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])),
            MutationInputProperties::REPEAT_PASSWORD => trim($this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])),
        ];
    }
}
