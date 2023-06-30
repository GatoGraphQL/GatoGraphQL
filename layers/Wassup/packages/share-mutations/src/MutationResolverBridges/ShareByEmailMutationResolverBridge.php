<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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
        if ($this->shareByEmailMutationResolver === null) {
            /** @var ShareByEmailMutationResolver */
            $shareByEmailMutationResolver = $this->instanceManager->getInstance(ShareByEmailMutationResolver::class);
            $this->shareByEmailMutationResolver = $shareByEmailMutationResolver;
        }
        return $this->shareByEmailMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getShareByEmailMutationResolver();
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $mutationData['name'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME])->getValue([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME]);
        $mutationData['email'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL])->getValue([PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL]);
        $mutationData['message'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE]);
        $mutationData['target-url'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL]);
        $mutationData['target-title'] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE]);
    }
}
