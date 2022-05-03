<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolverBridges;

use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_Volunteering_Module_Processor_TextFormInputs;
use PoP_Volunteering_Module_Processor_TextareaFormInputs;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\VolunteerMutations\MutationResolvers\VolunteerMutationResolver;

class VolunteerMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?VolunteerMutationResolver $volunteerMutationResolver = null;

    final public function setVolunteerMutationResolver(VolunteerMutationResolver $volunteerMutationResolver): void
    {
        $this->volunteerMutationResolver = $volunteerMutationResolver;
    }
    final protected function getVolunteerMutationResolver(): VolunteerMutationResolver
    {
        return $this->volunteerMutationResolver ??= $this->instanceManager->getInstance(VolunteerMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getVolunteerMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->getModuleProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->getModuleProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'phone' => $this->getModuleProcessorManager()->getProcessor([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::MODULE_FORMINPUT_PHONE])->getValue([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::MODULE_FORMINPUT_PHONE]),
            'whyvolunteer' => $this->getModuleProcessorManager()->getProcessor([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYVOLUNTEER])->getValue([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYVOLUNTEER]),
            'target-id' => $this->getModuleProcessorManager()->getProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST]),
        );

        return $form_data;
    }
}
