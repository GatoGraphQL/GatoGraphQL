<?php

abstract class PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase
{
    public function getSelectedComponent(array $component)
    {
        return [GD_EM_Module_Processor_LocationTypeaheadsSelectedLayouts::class, GD_EM_Module_Processor_LocationTypeaheadsSelectedLayouts::COMPONENT_LAYOUTLOCATION_CARD];
    }
}
