<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\Wassup\MutationResolvers\GravityFormsNewsletterUnsubscriptionMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class GravityFormsNewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected ?GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver = null;
    
    public function setGravityFormsNewsletterUnsubscriptionMutationResolver(GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver): void
    {
        $this->gravityFormsNewsletterUnsubscriptionMutationResolver = $gravityFormsNewsletterUnsubscriptionMutationResolver;
    }
    protected function getGravityFormsNewsletterUnsubscriptionMutationResolver(): GravityFormsNewsletterUnsubscriptionMutationResolver
    {
        return $this->gravityFormsNewsletterUnsubscriptionMutationResolver ??= $this->getInstanceManager()->getInstance(GravityFormsNewsletterUnsubscriptionMutationResolver::class);
    }

    //#[Required]
    final public function autowireGravityFormsNewsletterUnsubscriptionMutationResolverBridge(
        GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver,
    ): void {
        $this->gravityFormsNewsletterUnsubscriptionMutationResolver = $gravityFormsNewsletterUnsubscriptionMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGravityFormsNewsletterUnsubscriptionMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = array(
            'email' => $this->getModuleProcessorManager()->getProcessor([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL])->getValue([\PoP_Newsletter_Module_Processor_TextFormInputs::class, \PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL]),
        );

        return $form_data;
    }
}
