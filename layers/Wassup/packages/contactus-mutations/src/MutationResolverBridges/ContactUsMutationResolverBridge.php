<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUsMutations\MutationResolvers\ContactUsMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use Symfony\Contracts\Service\Attribute\Required;

class ContactUsMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?ContactUsMutationResolver $contactUsMutationResolver = null;

    public function setContactUsMutationResolver(ContactUsMutationResolver $contactUsMutationResolver): void
    {
        $this->contactUsMutationResolver = $contactUsMutationResolver;
    }
    protected function getContactUsMutationResolver(): ContactUsMutationResolver
    {
        return $this->contactUsMutationResolver ??= $this->instanceManager->getInstance(ContactUsMutationResolver::class);
    }

    //#[Required]
    final public function autowireContactUsMutationResolverBridge(
        ContactUsMutationResolver $contactUsMutationResolver,
    ): void {
        $this->contactUsMutationResolver = $contactUsMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getContactUsMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->getModuleProcessorManager()->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->getModuleProcessorManager()->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'subject' => $this->getModuleProcessorManager()->getProcessor([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT])->getValue([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT]),
            'message' => $this->getModuleProcessorManager()->getProcessor([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE])->getValue([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE]),
        );

        return $form_data;
    }
}
