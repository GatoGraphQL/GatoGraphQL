<?php

class Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES = 'layout-marknotificationasread-bgcolorstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES = 'layout-marknotificationasunread-bgcolorstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES],
        );
    }

    public function getElemTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
                return '.preview.notification-layout';
        }

        return parent::getElemTarget($component, $props);
    }
    
    public function getElemStyles(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
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



