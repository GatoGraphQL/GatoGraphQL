<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostMutations\MutationResolverBridges;

use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

abstract class AbstractCreateUpdateLocationPostMutationResolverBridge extends AbstractCreateUpdateCustomPostMutationResolverBridge
{
    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $locations = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, \PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP])->getValue([\PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, \PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP]);
        $form_data = array_merge(
            $form_data,
            array(
                'locations' => $locations ?? array(),
            )
        );

        return $form_data;
    }

    protected function volunteer()
    {
        return true;
    }
}
