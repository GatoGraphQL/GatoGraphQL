<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP_Newsletter_Module_Processor_TextFormInputs;
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

    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('name', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTERNAME])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTERNAME]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
