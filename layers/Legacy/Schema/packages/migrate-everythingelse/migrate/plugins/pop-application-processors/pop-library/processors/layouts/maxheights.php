<?php

class PoP_Module_Processor_MaxHeightLayouts extends PoP_Module_Processor_MaxHeightLayoutsBase
{
    public final const COMPONENT_LAYOUT_MAXHEIGHT_POSTCONTENT = 'layout-maxheight-postcontent';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MAXHEIGHT_POSTCONTENT],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MAXHEIGHT_POSTCONTENT:
                $ret[] = [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::COMPONENT_LAYOUT_CONTENT_POSTFEED];
                break;
        }

        return $ret;
    }

    public function getMaxheight(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MAXHEIGHT_POSTCONTENT:

                return '380';
        }

        return parent::getMaxheight($component, $props);
    }
}



