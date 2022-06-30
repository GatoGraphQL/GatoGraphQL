<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_Module_Processor_TextareaFormInputs;
use PoP_Module_Processor_TextFormInputs;
use PoP_Share_Module_Processor_TextFormInputs;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\ShareMutations\MutationResolvers\ShareByEmailMutationResolver;

class ShareByEmailMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    private ?ShareByEmailMutationResolver $shareByEmailMutationResolver = null;

    final public function setShareByEmailMutationResolver(ShareByEmailMutationResolver $shareByEmailMutationResolver): void
    {
        $this->shareByEmailMutationResolver = $shareByEmailMutationResolver;
    }
    final protected function getShareByEmailMutationResolver(): ShareByEmailMutationResolver
    {
        return $this->shareByEmailMutationResolver ??= $this->instanceManager->getInstance(ShareByEmailMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getShareByEmailMutationResolver();
    }

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument('name', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('email', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL])->getValue([PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('message', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('target-url', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('target-title', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
