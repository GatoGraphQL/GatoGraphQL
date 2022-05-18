<?php

abstract class PoP_Module_Processor_UserHiddenInputAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getSelectedModule(array $component)
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
    }
}
