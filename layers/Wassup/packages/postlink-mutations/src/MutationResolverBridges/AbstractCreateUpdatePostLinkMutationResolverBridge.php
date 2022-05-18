<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\PostMutations\MutationResolverBridges\AbstractCreateUpdatePostMutationResolverBridge;

abstract class AbstractCreateUpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            $form_data = array_merge(
                $form_data,
                array(
                    'linkaccess' => $this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS])->getValue([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS]),
                )
            );
        }

        return $form_data;
    }

    protected function getEditorInput()
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
