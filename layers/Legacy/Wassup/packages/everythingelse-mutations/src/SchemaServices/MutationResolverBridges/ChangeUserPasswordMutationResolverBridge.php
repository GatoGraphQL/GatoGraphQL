<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\ChangeUserPasswordMutationResolver;

class ChangeUserPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver = null;
    
    final public function setChangeUserPasswordMutationResolver(ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver): void
    {
        $this->changeUserPasswordMutationResolver = $changeUserPasswordMutationResolver;
    }
    final protected function getChangeUserPasswordMutationResolver(): ChangeUserPasswordMutationResolver
    {
        return $this->changeUserPasswordMutationResolver ??= $this->instanceManager->getInstance(ChangeUserPasswordMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getChangeUserPasswordMutationResolver();
    }

    public function getFormData(): array
    {
        $user_id = \PoP\Root\App::getState('current-user-id');
        $form_data = array(
            'user_id' => $user_id,
            'current_password' => $this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_CURRENTPASSWORD])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_CURRENTPASSWORD]),
            'password' => $this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORD])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORD]),
            'repeat_password' => $this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT])->getValue([\PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, \PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT])
        );

        return $form_data;
    }
}
