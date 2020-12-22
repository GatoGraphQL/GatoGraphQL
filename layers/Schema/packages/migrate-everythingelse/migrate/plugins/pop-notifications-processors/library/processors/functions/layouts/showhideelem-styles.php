<?php

class GD_AAL_Module_Processor_ShowHideElemStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES = 'layout-marknotificationasread-showelemstyles';
    public const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES = 'layout-marknotificationasread-hideelemstyles';
    public const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES = 'layout-marknotificationasunread-showelemstyles';
    public const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES = 'layout-marknotificationasunread-hideelemstyles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES],
        );
    }

    public function getElemTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASREAD;

            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
        }

        return parent::getElemTarget($module, $props);
    }
    
    public function getElemStyles(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
                return array(
                    'display' => 'block'
                );

            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:
                return array(
                    'display' => 'none'
                );
        }

        return parent::getElemStyles($module, $props);
    }
}



