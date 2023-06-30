<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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
        if ($this->flagCustomPostMutationResolver === null) {
            /** @var FlagCustomPostMutationResolver */
            $flagCustomPostMutationResolver = $this->instanceManager->getInstance(FlagCustomPostMutationResolver::class);
            $this->flagCustomPostMutationResolver = $flagCustomPostMutationResolver;
        }
        return $this->flagCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getFlagCustomPostMutationResolver();
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $mutationData['name'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]);
        $mutationData['email'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL]);
        $mutationData['whyflag'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG])->getValue([PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG]);
        $mutationData['target-id'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST]);
    }
}
