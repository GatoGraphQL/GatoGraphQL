<?php

abstract class PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase
{
    public function getSelectedComponent(array $component)
    {
        return [PoP_Module_Processor_PostCardLayouts::class, PoP_Module_Processor_PostCardLayouts::COMPONENT_LAYOUTPOST_CARD];
    }
}
