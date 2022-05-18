<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolverBridges;

use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_ContentCreation_Module_Processor_TextareaFormInputs;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FlagMutations\MutationResolvers\FlagCustomPostMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class FlagCustomPostMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?FlagCustomPostMutationResolver $flagCustomPostMutationResolver = null;

    final public function setFlagCustomPostMutationResolver(FlagCustomPostMutationResolver $flagCustomPostMutationResolver): void
    {
        $this->flagCustomPostMutationResolver = $flagCustomPostMutationResolver;
    }
    final protected function getFlagCustomPostMutationResolver(): FlagCustomPostMutationResolver
    {
        return $this->flagCustomPostMutationResolver ??= $this->instanceManager->getInstance(FlagCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getFlagCustomPostMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->getComponentProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]),
            'email' => $this->getComponentProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]),
            'whyflag' => $this->getComponentProcessorManager()->getProcessor([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG])->getValue([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG]),
            'target-id' => $this->getComponentProcessorManager()->getProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST]),
        );

        return $form_data;
    }
}
