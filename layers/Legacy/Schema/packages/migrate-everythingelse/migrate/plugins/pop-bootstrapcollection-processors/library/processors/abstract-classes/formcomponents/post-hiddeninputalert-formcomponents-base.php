<?php

abstract class PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getSelectedModule(array $module)
    {
        return [PoP_Module_Processor_PostCardLayouts::class, PoP_Module_Processor_PostCardLayouts::MODULE_LAYOUTPOST_CARD];
    }
}
