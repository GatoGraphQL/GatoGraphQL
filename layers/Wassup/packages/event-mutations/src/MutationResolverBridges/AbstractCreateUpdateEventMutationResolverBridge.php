<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventMutations\MutationResolverBridges;

use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class AbstractCreateUpdateEventMutationResolverBridge extends AbstractCreateUpdateCustomPostMutationResolverBridge
{
    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data['location'] = $moduleprocessor_manager->getProcessor([\PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, \PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP])->getValue([\PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, \PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP]);
        $form_data['when'] = $moduleprocessor_manager->getProcessor([\PoP_Module_Processor_DateRangeComponentInputs::class, \PoP_Module_Processor_DateRangeComponentInputs::MODULE_FORMINPUT_DATERANGETIMEPICKER])->getValue([\PoP_Module_Processor_DateRangeComponentInputs::class, \PoP_Module_Processor_DateRangeComponentInputs::MODULE_FORMINPUT_DATERANGETIMEPICKER]);

        return $form_data;
    }

    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    protected function modifyDataProperties(array &$data_properties, $result_id): void
    {
        parent::modifyDataProperties($data_properties, $result_id);
        $data_properties[DataloadingConstants::QUERYARGS]['status'] = array('pending', 'draft', 'published');
    }
}
