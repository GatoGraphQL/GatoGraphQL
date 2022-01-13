<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES = 'layout-marknotificationasread-bgcolorstyles';
    public const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES = 'layout-marknotificationasunread-bgcolorstyles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
                return '.preview.notification-layout';
        }

        return parent::getElemTarget($module, $props);
    }
    
    public function getElemStyles(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
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



