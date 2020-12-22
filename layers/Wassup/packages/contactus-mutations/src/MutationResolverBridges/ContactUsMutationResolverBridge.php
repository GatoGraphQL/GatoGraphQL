<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolverBridges;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\ContactUsMutations\MutationResolvers\ContactUsMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class ContactUsMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return ContactUsMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data = array(
            'name' => $moduleprocessor_manager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $moduleprocessor_manager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'subject' => $moduleprocessor_manager->getProcessor([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT])->getValue([\PoP_ContactUs_Module_Processor_TextFormInputs::class, \PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT]),
            'message' => $moduleprocessor_manager->getProcessor([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE])->getValue([\PoP_ContactUs_Module_Processor_TextareaFormInputs::class, \PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE]),
        );

        return $form_data;
    }
}

