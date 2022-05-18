<?php

class PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasread-topbgcolorstyles';
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasunread-topbgcolorstyles';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
                return '#ps-top .preview.notification-layout';
        }

        return parent::getElemTarget($componentVariation, $props);
    }
    
    public function getElemStyles(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
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



