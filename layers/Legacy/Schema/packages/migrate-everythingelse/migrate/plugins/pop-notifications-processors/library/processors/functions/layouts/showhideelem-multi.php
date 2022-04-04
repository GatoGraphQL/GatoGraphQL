<?php

class GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasread-showhideelemstyles';
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES = 'layout-marknotificationasunread-showhideelemstyles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES];
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES];
                break;
            
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES];
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES];
                break;
        }

        return $ret;
    }
}



