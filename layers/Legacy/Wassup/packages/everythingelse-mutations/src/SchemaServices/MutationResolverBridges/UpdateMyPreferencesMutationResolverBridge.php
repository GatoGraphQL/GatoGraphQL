<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateMyPreferencesMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdateMyPreferencesMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected UpdateMyPreferencesMutationResolver $updateMyPreferencesMutationResolver;
    
    #[Required]
    final public function autowireUpdateMyPreferencesMutationResolverBridge(
        UpdateMyPreferencesMutationResolver $updateMyPreferencesMutationResolver,
    ): void {
        $this->updateMyPreferencesMutationResolver = $updateMyPreferencesMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateMyPreferencesMutationResolver();
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $form_data = array(
            'user_id' => $user_id,
            // We can just get the value for any one forminput from the My Preferences form, since they all have the same name (and even if the forminput was actually removed from the form!)
            'userPreferences' => $this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_UserProfileCheckboxFormInputs::class, \PoP_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST])->getValue([\PoP_Module_Processor_UserProfileCheckboxFormInputs::class, \PoP_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST]),
        );

        return $form_data;
    }
}
