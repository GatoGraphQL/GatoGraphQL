<?php

class Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES = 'layout-marknotificationasread-bgcolorstyles';
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES = 'layout-marknotificationasunread-bgcolorstyles';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
                return '.preview.notification-layout';
        }

        return parent::getElemTarget($componentVariation, $props);
    }
    
    public function getElemStyles(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
                return array(
                    'background-color' => \PoP\Root\App::applyFilters(
                        'PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts:bgcolor',
                        'transparent',
                        $componentVariation
                    )
                );
        }

        return parent::getElemStyles($componentVariation, $props);
    }
}



