<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_Volunteering_Module_Processor_TextareaFormInputs;
use PoP_Volunteering_Module_Processor_TextFormInputs;
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
        $withArgumentsAST->addArgument(new Argument('name', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('phone', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_PHONE])->getValue([PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_PHONE]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('whyvolunteer', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYVOLUNTEER])->getValue([PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYVOLUNTEER]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('target-id', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
