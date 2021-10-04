<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterUnsubscriptionMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class NewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver;

    #[Required]
    final public function autowireNewsletterUnsubscriptionMutationResolverBridge(
        NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver,
    ): void {
        $this->newsletterUnsubscriptionMutationResolver = $newsletterUnsubscriptionMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->newsletterUnsubscriptionMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            'email' => $this->moduleProcessorManager->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL]),
            'verificationcode' => $this->moduleProcessorManager->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE]),
        );

        return $form_data;
    }
}
