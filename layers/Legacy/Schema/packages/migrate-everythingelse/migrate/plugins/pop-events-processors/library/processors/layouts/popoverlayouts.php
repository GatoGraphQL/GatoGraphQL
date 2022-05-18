<?php

class GD_EM_Module_Processor_CustomPopoverLayouts extends PoP_Module_Processor_PopoverLayoutsBase
{
    public final const MODULE_LAYOUT_POPOVER_EVENT = 'layout-popover-event';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POPOVER_EVENT],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                return [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER];
        }

        return parent::getLayoutSubmodule($component);
    }

    public function getLayoutContentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                return [PoP_Module_Processor_CalendarContentLayouts::class, PoP_Module_Processor_CalendarContentLayouts::COMPONENT_LAYOUTCALENDAR_CONTENT_POPOVER];
        }

        return parent::getLayoutContentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                // Use no Author popover
                $this->appendProp($component, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($component, $props);
    }

    // function getModulePath(array $component, array &$props) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_LAYOUT_POPOVER_EVENT:

    //             return $component;
    //     }

    //     return parent::getModulePath($component, $props);
    // }
}



