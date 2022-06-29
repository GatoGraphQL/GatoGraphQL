<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Newsletter_Module_Processor_TextFormInputs;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterUnsubscriptionMutationResolver;

class NewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver = null;

    final public function setNewsletterUnsubscriptionMutationResolver(NewsletterUnsubscriptionMutationResolver $newsletterUnsubscriptionMutationResolver): void
    {
        $this->newsletterUnsubscriptionMutationResolver = $newsletterUnsubscriptionMutationResolver;
    }
    final protected function getNewsletterUnsubscriptionMutationResolver(): NewsletterUnsubscriptionMutationResolver
    {
        return $this->newsletterUnsubscriptionMutationResolver ??= $this->instanceManager->getInstance(NewsletterUnsubscriptionMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getNewsletterUnsubscriptionMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('verificationcode', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
