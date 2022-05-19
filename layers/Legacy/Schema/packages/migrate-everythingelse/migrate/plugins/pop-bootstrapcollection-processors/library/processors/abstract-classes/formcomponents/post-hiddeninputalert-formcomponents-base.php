<?php

abstract class PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getSelectedComponent(array $component)
    {
        return [PoP_Module_Processor_PostCardLayouts::class, PoP_Module_Processor_PostCardLayouts::COMPONENT_LAYOUTPOST_CARD];
    }
}
