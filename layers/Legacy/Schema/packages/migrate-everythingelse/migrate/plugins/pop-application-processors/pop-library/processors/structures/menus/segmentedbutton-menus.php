<?php

class PoP_Module_Processor_SegmentedButtonMenus extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_SEGMENTEDBUTTONMENU = 'segmentedbuttonmenu';
    public final const COMPONENT_NAVIGATORSEGMENTEDBUTTONMENU = 'navigatorsegmentedbuttonmenu';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SEGMENTEDBUTTONMENU],
            [self::class, self::COMPONENT_NAVIGATORSEGMENTEDBUTTONMENU],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SEGMENTEDBUTTONMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_SEGMENTEDBUTTON];

            case self::COMPONENT_NAVIGATORSEGMENTEDBUTTONMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON];
        }
    }
}


