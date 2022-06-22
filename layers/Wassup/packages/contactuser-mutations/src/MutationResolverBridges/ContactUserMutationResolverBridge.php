<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolverBridges;

use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_SocialNetwork_Module_Processor_TextFormInputs;
use PoP_SocialNetwork_Module_Processor_TextareaFormInputs;
use PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUserMutations\MutationResolvers\ContactUserMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class ContactUserMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?ContactUserMutationResolver $contactUserMutationResolver = null;

    final public function setContactUserMutationResolver(ContactUserMutationResolver $contactUserMutationResolver): void
    {
        $this->contactUserMutationResolver = $contactUserMutationResolver;
    }
    final protected function getContactUserMutationResolver(): ContactUserMutationResolver
    {
        return $this->contactUserMutationResolver ??= $this->instanceManager->getInstance(ContactUserMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getContactUserMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]),
            'email' => $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]),
            'subject' => $this->getComponentProcessorManager()->getComponentProcessor([PoP_SocialNetwork_Module_Processor_TextFormInputs::class, PoP_SocialNetwork_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_MESSAGESUBJECT])->getValue([PoP_SocialNetwork_Module_Processor_TextFormInputs::class, PoP_SocialNetwork_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_MESSAGESUBJECT]),
            'message' => $this->getComponentProcessorManager()->getComponentProcessor([PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, PoP_SocialNetwork_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGETOUSER])->getValue([PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, PoP_SocialNetwork_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGETOUSER]),
            'target-id' => $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_USER])->getValue([PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_USER]),
        );

        return $form_data;
    }
}
