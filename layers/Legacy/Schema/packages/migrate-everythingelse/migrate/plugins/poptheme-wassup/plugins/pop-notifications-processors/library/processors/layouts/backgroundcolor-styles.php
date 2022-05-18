<?php

class PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasread-topbgcolorstyles';
    public final const MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES = 'layout-marknotificationasunread-topbgcolorstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES],
            [self::class, self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
                return '#ps-top .preview.notification-layout';
        }

        return parent::getElemTarget($component, $props);
    }
    
    public function getElemStyles(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            case self::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
                return array(
                    'background-color' => \PoP\Root\App::applyFilters(
                        'PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts:bgcolor',
                        'transparent',
                        $component
                    )
                );
        }

        return parent::getElemStyles($component, $props);
    }
}



