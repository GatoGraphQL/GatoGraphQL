<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\Wassup\MutationResolvers\GravityFormsNewsletterUnsubscriptionMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class GravityFormsNewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver;
    
    #[Required]
    final public function autowireGravityFormsNewsletterUnsubscriptionMutationResolverBridge(
        GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver,
    ): void {
        $this->gravityFormsNewsletterUnsubscriptionMutationResolver = $gravityFormsNewsletterUnsubscriptionMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->gravityFormsNewsletterUnsubscriptionMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            'email' => $this->moduleProcessorManager->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL]),
        );

        return $form_data;
    }
}
