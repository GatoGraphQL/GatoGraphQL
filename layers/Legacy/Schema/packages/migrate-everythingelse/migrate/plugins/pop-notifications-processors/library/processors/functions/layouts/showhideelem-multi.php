<?php

class GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasread-showhideelemstyles';
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasunread-showhideelemstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

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



