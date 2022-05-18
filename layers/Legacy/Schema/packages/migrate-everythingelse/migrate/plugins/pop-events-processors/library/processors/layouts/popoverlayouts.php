<?php

class GD_EM_Module_Processor_CustomPopoverLayouts extends PoP_Module_Processor_PopoverLayoutsBase
{
    public final const MODULE_LAYOUT_POPOVER_EVENT = 'layout-popover-event';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POPOVER_EVENT],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                return [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_POPOVER];
        }

        return parent::getLayoutSubmodule($module);
    }

    public function getLayoutContentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                return [PoP_Module_Processor_CalendarContentLayouts::class, PoP_Module_Processor_CalendarContentLayouts::MODULE_LAYOUTCALENDAR_CONTENT_POPOVER];
        }

        return parent::getLayoutContentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                // Use no Author popover
                $this->appendProp($module, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($module, $props);
    }

    // function getModulePath(array $module, array &$props) {

    //     switch ($module[1]) {

    //         case self::MODULE_LAYOUT_POPOVER_EVENT:

    //             return $module;
    //     }

    //     return parent::getModulePath($module, $props);
    // }
}



