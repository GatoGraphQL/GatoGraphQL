<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_Volunteering_Module_Processor_TextFormInputs;
use PoP_Volunteering_Module_Processor_TextareaFormInputs;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\VolunteerMutations\MutationResolvers\VolunteerMutationResolver;

class VolunteerMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?VolunteerMutationResolver $volunteerMutationResolver = null;

    final public function setVolunteerMutationResolver(VolunteerMutationResolver $volunteerMutationResolver): void
    {
        $this->volunteerMutationResolver = $volunteerMutationResolver;
    }
    final protected function getVolunteerMutationResolver(): VolunteerMutationResolver
    {
        return $this->volunteerMutationResolver ??= $this->instanceManager->getInstance(VolunteerMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getVolunteerMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('name', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('email', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('phone', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_PHONE])->getValue([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_PHONE]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('whyvolunteer', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYVOLUNTEER])->getValue([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYVOLUNTEER]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('target-id', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
