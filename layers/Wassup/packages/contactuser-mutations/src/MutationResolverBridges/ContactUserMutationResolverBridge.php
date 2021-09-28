<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUserMutations\MutationResolvers\ContactUserMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class ContactUserMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected ContactUserMutationResolver $contactUserMutationResolver;

    #[Required]
    public function autowireContactUserMutationResolverBridge(
        ContactUserMutationResolver $contactUserMutationResolver,
    ): void {
        $this->contactUserMutationResolver = $contactUserMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->contactUserMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'subject' => $this->moduleProcessorManager->getProcessor([\PoP_SocialNetwork_Module_Processor_TextFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextFormInputs::MODULE_FORMINPUT_MESSAGESUBJECT])->getValue([\PoP_SocialNetwork_Module_Processor_TextFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextFormInputs::MODULE_FORMINPUT_MESSAGESUBJECT]),
            'message' => $this->moduleProcessorManager->getProcessor([\PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGETOUSER])->getValue([\PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, \PoP_SocialNetwork_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGETOUSER]),
            'target-id' => $this->moduleProcessorManager->getProcessor([\PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_USER])->getValue([\PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_USER]),
        );

        return $form_data;
    }
}
