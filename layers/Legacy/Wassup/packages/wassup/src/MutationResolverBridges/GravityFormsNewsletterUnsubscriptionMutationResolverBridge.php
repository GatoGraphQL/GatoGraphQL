<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
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
        if ($this->gravityFormsNewsletterUnsubscriptionMutationResolver === null) {
            /** @var GravityFormsNewsletterUnsubscriptionMutationResolver */
            $gravityFormsNewsletterUnsubscriptionMutationResolver = $this->instanceManager->getInstance(GravityFormsNewsletterUnsubscriptionMutationResolver::class);
            $this->gravityFormsNewsletterUnsubscriptionMutationResolver = $gravityFormsNewsletterUnsubscriptionMutationResolver;
        }
        return $this->gravityFormsNewsletterUnsubscriptionMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGravityFormsNewsletterUnsubscriptionMutationResolver();
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $mutationData['email'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL])->getValue([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAIL]);
    }
}
