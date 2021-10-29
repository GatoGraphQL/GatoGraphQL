<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUserMutations\MutationResolvers\ContactUserMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use Symfony\Contracts\Service\Attribute\Required;

class ContactUserMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?ContactUserMutationResolver $contactUserMutationResolver = null;

    public function setContactUserMutationResolver(ContactUserMutationResolver $contactUserMutationResolver): void
    {
        $this->contactUserMutationResolver = $contactUserMutationResolver;
    }
    protected function getContactUserMutationResolver(): ContactUserMutationResolver
    {
        return $this->contactUserMutationResolver ??= $this->instanceManager->getInstance(ContactUserMutationResolver::class);
    }

    //#[Required]
    final public function autowireContactUserMutationResolverBridge(
        ContactUserMutationResolver $contactUserMutationResolver,
    ): void {
        $this->contactUserMutationResolver = $contactUserMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getContactUserMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->getModuleProcessorManager()->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->getModuleProcessorManager()->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'subject' => $this->getModuleProcessorManager()->getProcessor([\PoP_SocialNetwork_Module_Processor_TextFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextFormInputs::MODULE_FORMINPUT_MESSAGESUBJECT])->getValue([\PoP_SocialNetwork_Module_Processor_TextFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextFormInputs::MODULE_FORMINPUT_MESSAGESUBJECT]),
            'message' => $this->getModuleProcessorManager()->getProcessor([\PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGETOUSER])->getValue([\PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGETOUSER]),
            'target-id' => $this->getModuleProcessorManager()->getProcessor([\PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_USER])->getValue([\PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_USER]),
        );

        return $form_data;
    }
}
