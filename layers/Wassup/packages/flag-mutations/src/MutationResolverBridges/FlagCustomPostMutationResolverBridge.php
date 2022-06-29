<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP_ContentCreation_Module_Processor_TextareaFormInputs;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoPSitesWassup\FlagMutations\MutationResolvers\FlagCustomPostMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class FlagCustomPostMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?FlagCustomPostMutationResolver $flagCustomPostMutationResolver = null;

    final public function setFlagCustomPostMutationResolver(FlagCustomPostMutationResolver $flagCustomPostMutationResolver): void
    {
        $this->flagCustomPostMutationResolver = $flagCustomPostMutationResolver;
    }
    final protected function getFlagCustomPostMutationResolver(): FlagCustomPostMutationResolver
    {
        return $this->flagCustomPostMutationResolver ??= $this->instanceManager->getInstance(FlagCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getFlagCustomPostMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new Argument('name', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('whyflag', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG])->getValue([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('target-id', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
