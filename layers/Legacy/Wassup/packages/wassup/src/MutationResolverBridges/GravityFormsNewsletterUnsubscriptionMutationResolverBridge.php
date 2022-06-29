<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Newsletter_Module_Processor_TextFormInputs;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\Wassup\MutationResolvers\GravityFormsNewsletterUnsubscriptionMutationResolver;

class GravityFormsNewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver = null;
    
    final public function setGravityFormsNewsletterUnsubscriptionMutationResolver(GravityFormsNewsletterUnsubscriptionMutationResolver $gravityFormsNewsletterUnsubscriptionMutationResolver): void
    {
        $this->gravityFormsNewsletterUnsubscriptionMutationResolver = $gravityFormsNewsletterUnsubscriptionMutationResolver;
    }
    final protected function getGravityFormsNewsletterUnsubscriptionMutationResolver(): GravityFormsNewsletterUnsubscriptionMutationResolver
    {
        return $this->gravityFormsNewsletterUnsubscriptionMutationResolver ??= $this->instanceManager->getInstance(GravityFormsNewsletterUnsubscriptionMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGravityFormsNewsletterUnsubscriptionMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
