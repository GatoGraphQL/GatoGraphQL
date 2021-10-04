<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUsMutations\MutationResolvers\ContactUsMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use Symfony\Contracts\Service\Attribute\Required;

class ContactUsMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected ContactUsMutationResolver $contactUsMutationResolver;

    #[Required]
    final public function autowireContactUsMutationResolverBridge(
        ContactUsMutationResolver $contactUsMutationResolver,
    ): void {
        $this->contactUsMutationResolver = $contactUsMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->contactUsMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'subject' => $this->moduleProcessorManager->getProcessor([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT])->getValue([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT]),
            'message' => $this->moduleProcessorManager->getProcessor([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE])->getValue([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE]),
        );

        return $form_data;
    }
}
