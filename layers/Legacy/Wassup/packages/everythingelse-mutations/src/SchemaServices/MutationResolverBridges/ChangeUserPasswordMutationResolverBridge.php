<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\ChangeUserPasswordMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class ChangeUserPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver;
    
    #[Required]
    public function autowireChangeUserPasswordMutationResolverBridge(
        ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver,
    ): void {
        $this->changeUserPasswordMutationResolver = $changeUserPasswordMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->changeUserPasswordMutationResolver;
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];
        $form_data = array(
            'user_id' => $user_id,
            'current_password' => $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_CURRENTPASSWORD])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_CURRENTPASSWORD]),
            'password' => $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORD])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORD]),
            'repeat_password' => $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT])
        );

        return $form_data;
    }
}
