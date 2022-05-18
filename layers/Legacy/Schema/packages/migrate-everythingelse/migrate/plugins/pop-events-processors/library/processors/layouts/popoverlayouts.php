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

    public function getLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                return [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_POPOVER];
        }

        return parent::getLayoutSubmodule($componentVariation);
    }

    public function getLayoutContentSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                return [PoP_Module_Processor_CalendarContentLayouts::class, PoP_Module_Processor_CalendarContentLayouts::MODULE_LAYOUTCALENDAR_CONTENT_POPOVER];
        }

        return parent::getLayoutContentSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POPOVER_EVENT:
                // Use no Author popover
                $this->appendProp($componentVariation, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    // function getModulePath(array $componentVariation, array &$props) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_LAYOUT_POPOVER_EVENT:

    //             return $componentVariation;
    //     }

    //     return parent::getModulePath($componentVariation, $props);
    // }
}



