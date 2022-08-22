<?php

class GD_EM_Module_Processor_CustomPopoverLayouts extends PoP_Module_Processor_PopoverLayoutsBase
{
    public final const COMPONENT_LAYOUT_POPOVER_EVENT = 'layout-popover-event';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POPOVER_EVENT,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                return [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER];
        }

        return parent::getLayoutSubcomponent($component);
    }

    public function getLayoutContentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                return [PoP_Module_Processor_CalendarContentLayouts::class, PoP_Module_Processor_CalendarContentLayouts::COMPONENT_LAYOUTCALENDAR_CONTENT_POPOVER];
        }

        return parent::getLayoutContentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POPOVER_EVENT:
                // Use no Author popover
                $this->appendProp($component, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($component, $props);
    }

    // function getComponentPath(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     switch ($component->name) {

    //         case self::COMPONENT_LAYOUT_POPOVER_EVENT:

    //             return $component;
    //     }

    //     return parent::getComponentPath($component, $props);
    // }
}



