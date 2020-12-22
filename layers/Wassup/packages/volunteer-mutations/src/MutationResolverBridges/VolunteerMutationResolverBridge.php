<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolverBridges;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\VolunteerMutations\MutationResolvers\VolunteerMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class VolunteerMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return VolunteerMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data = array(
            'name' => $moduleprocessor_manager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $moduleprocessor_manager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'phone' => $moduleprocessor_manager->getProcessor([\PoP_Volunteering_Module_Processor_TextFormInputs::class, \PoP_Volunteering_Module_Processor_TextFormInputs::MODULE_FORMINPUT_PHONE])->getValue([\PoP_Volunteering_Module_Processor_TextFormInputs::class, \PoP_Volunteering_Module_Processor_TextFormInputs::MODULE_FORMINPUT_PHONE]),
            'whyvolunteer' => $moduleprocessor_manager->getProcessor([\PoP_Volunteering_Module_Processor_TextareaFormInputs::class, \PoP_Volunteering_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYVOLUNTEER])->getValue([\PoP_Volunteering_Module_Processor_TextareaFormInputs::class, \PoP_Volunteering_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYVOLUNTEER]),
            'target-id' => $moduleprocessor_manager->getProcessor([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST])->getValue([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST]),
        );

        return $form_data;
    }

}

