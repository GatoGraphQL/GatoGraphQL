<?php

class PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasread-topbgcolorstyles';
    public const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasunread-topbgcolorstyles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
                return '#ps-top .preview.notification-layout';
        }

        return parent::getElemTarget($module, $props);
    }
    
    public function getElemStyles(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
                return array(
                    'background-color' => \PoP\Root\App::getHookManager()->applyFilters(
                        'PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts:bgcolor',
                        'transparent',
                        $module
                    )
                );
        }

        return parent::getElemStyles($module, $props);
    }
}



