<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolver\NewsletterUnsubscriptionMutationResolver;

class NewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return NewsletterUnsubscriptionMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data = array(
            'email' => $moduleprocessor_manager->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL]),
            'verificationcode' => $moduleprocessor_manager->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE]),
        );

        return $form_data;
    }
}
