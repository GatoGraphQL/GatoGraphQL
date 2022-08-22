<?php

class GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasread-showhideelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasunread-showhideelemstyles';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES,
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
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



