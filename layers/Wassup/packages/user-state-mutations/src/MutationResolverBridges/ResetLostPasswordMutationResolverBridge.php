<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolvers\ResetLostPasswordMutationResolver;

class ResetLostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver;

    #[Required]
    public function autowireResetLostPasswordMutationResolverBridge(
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
