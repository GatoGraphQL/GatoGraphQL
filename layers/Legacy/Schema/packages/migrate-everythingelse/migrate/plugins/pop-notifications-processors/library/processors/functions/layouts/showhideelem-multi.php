<?php

class GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasread-showhideelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasunread-showhideelemstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES];
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES];
                break;
            
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES];
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES];
                break;
        }

        return $ret;
    }
}



