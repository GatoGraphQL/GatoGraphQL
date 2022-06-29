<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_ContactUs_Module_Processor_TextFormInputs;
use PoP_ContactUs_Module_Processor_TextareaFormInputs;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\ContactUsMutations\MutationResolvers\ContactUsMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class ContactUsMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?ContactUsMutationResolver $contactUsMutationResolver = null;

    final public function setContactUsMutationResolver(ContactUsMutationResolver $contactUsMutationResolver): void
    {
        $this->contactUsMutationResolver = $contactUsMutationResolver;
    }
    final protected function getContactUsMutationResolver(): ContactUsMutationResolver
    {
        return $this->contactUsMutationResolver ??= $this->instanceManager->getInstance(ContactUsMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getContactUsMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('name', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('email', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('subject', $this->getComponentProcessorManager()->getComponentProcessor([PoP_ContactUs_Module_Processor_TextFormInputs::class, PoP_ContactUs_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SUBJECT])->getValue([PoP_ContactUs_Module_Processor_TextFormInputs::class, PoP_ContactUs_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SUBJECT]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('message', $this->getComponentProcessorManager()->getComponentProcessor([PoP_ContactUs_Module_Processor_TextareaFormInputs::class, PoP_ContactUs_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGE])->getValue([PoP_ContactUs_Module_Processor_TextareaFormInputs::class, PoP_ContactUs_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGE]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
