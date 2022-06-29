<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Newsletter_Module_Processor_TextFormInputs;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterSubscriptionMutationResolver;

class NewsletterSubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?NewsletterSubscriptionMutationResolver $newsletterSubscriptionMutationResolver = null;

    final public function setNewsletterSubscriptionMutationResolver(NewsletterSubscriptionMutationResolver $newsletterSubscriptionMutationResolver): void
    {
        $this->newsletterSubscriptionMutationResolver = $newsletterSubscriptionMutationResolver;
    }
    final protected function getNewsletterSubscriptionMutationResolver(): NewsletterSubscriptionMutationResolver
    {
        return $this->newsletterSubscriptionMutationResolver ??= $this->instanceManager->getInstance(NewsletterSubscriptionMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getNewsletterSubscriptionMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('email', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('name', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTERNAME])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTERNAME]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
