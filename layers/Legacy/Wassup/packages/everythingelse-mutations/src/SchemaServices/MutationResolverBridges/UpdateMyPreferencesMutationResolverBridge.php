<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP_Module_Processor_UserProfileCheckboxFormInputs;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateMyPreferencesMutationResolver;

class UpdateMyPreferencesMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?UpdateMyPreferencesMutationResolver $updateMyPreferencesMutationResolver = null;
    
    final public function setUpdateMyPreferencesMutationResolver(UpdateMyPreferencesMutationResolver $updateMyPreferencesMutationResolver): void
    {
        $this->updateMyPreferencesMutationResolver = $updateMyPreferencesMutationResolver;
    }
    final protected function getUpdateMyPreferencesMutationResolver(): UpdateMyPreferencesMutationResolver
    {
        return $this->updateMyPreferencesMutationResolver ??= $this->instanceManager->getInstance(UpdateMyPreferencesMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateMyPreferencesMutationResolver();
    }

    public function getFormData(): array
    {
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $form_data = array(
            'user_id' => $user_id,
            // We can just get the value for any one forminput from the My Preferences form, since they all have the same name (and even if the forminput was actually removed from the form!)
            'userPreferences' => $this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST])->getValue([PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST]),
        );

        return $form_data;
    }
}
