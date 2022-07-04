<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\PostMutations\MutationResolverBridges\AbstractCreateUpdatePostMutationResolverBridge;

abstract class AbstractCreateUpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function appendMutationDataToFieldDataAccessor(\PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::appendMutationDataToFieldDataAccessor($fieldDataProvider);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            $fieldDataProvider->add('linkaccess', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS])->getValue([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS]));
        }
    }

    protected function getEditorInput()
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
