<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterUnsubscriptionMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class NewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver = null;

    public function setNewsletterUnsubscriptionMutationResolver(NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver): void
    {
        $this->newsletterUnsubscriptionMutationResolver = $newsletterUnsubscriptionMutationResolver;
    }
    protected function getNewsletterUnsubscriptionMutationResolver(): NewsletterUnsubscriptionMutationResolver
    {
        return $this->newsletterUnsubscriptionMutationResolver ??= $this->instanceManager->getInstance(NewsletterUnsubscriptionMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getNewsletterUnsubscriptionMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'email' => $this->getModuleProcessorManager()->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL]),
            'verificationcode' => $this->getModuleProcessorManager()->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE]),
        );

        return $form_data;
    }
}
