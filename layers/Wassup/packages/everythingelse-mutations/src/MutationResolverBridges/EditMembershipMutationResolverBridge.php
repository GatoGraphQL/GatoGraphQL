<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolvers\EditMembershipMutationResolver;

class EditMembershipMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return EditMembershipMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $vars = ApplicationState::getVars();
        $community = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $privileges = $moduleprocessor_manager->getProcessor([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES])->getValue([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES]);
        $tags = $moduleprocessor_manager->getProcessor([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERTAGS])->getValue([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERTAGS]);
        $form_data = array(
            'community' => $community,
            'user_id' => $_REQUEST[\PoPSchema\Users\Constants\InputNames::USER_ID] ?? null,
            // 'nonce' => $_REQUEST[POP_INPUTNAME_NONCE],
            'status' => trim($moduleprocessor_manager->getProcessor([\GD_URE_Module_Processor_SelectFormInputs::class, \GD_URE_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_MEMBERSTATUS])->getValue([\GD_URE_Module_Processor_SelectFormInputs::class, \GD_URE_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_MEMBERSTATUS])),
            'privileges' => $privileges ?? array(),
            'tags' => $tags ?? array(),
        );

        return $form_data;
    }
}
