<?php

class PoP_Module_Processor_MaxHeightLayouts extends PoP_Module_Processor_MaxHeightLayoutsBase
{
    public final const MODULE_LAYOUT_MAXHEIGHT_POSTCONTENT = 'layout-maxheight-postcontent';
    
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MAXHEIGHT_POSTCONTENT],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MAXHEIGHT_POSTCONTENT:
                $ret[] = [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POSTFEED];
                break;
        }

        return $ret;
    }

    public function getMaxheight(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MAXHEIGHT_POSTCONTENT:

                return '380';
        }

        return parent::getMaxheight($module, $props);
    }
}



