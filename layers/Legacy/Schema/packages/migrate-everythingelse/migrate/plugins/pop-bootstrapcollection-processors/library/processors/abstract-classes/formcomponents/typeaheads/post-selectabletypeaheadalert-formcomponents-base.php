<?php

abstract class PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase
{
    public function getSelectedModule(array $componentVariation)
    {
        return [PoP_Module_Processor_PostCardLayouts::class, PoP_Module_Processor_PostCardLayouts::MODULE_LAYOUTPOST_CARD];
    }
}
