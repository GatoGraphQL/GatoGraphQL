<?php

class GD_AAL_Module_Processor_ShowHideElemStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES = 'layout-marknotificationasread-showelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES = 'layout-marknotificationasread-hideelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES = 'layout-marknotificationasunread-showelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES = 'layout-marknotificationasunread-hideelemstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES],
            [self::class, self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES],
        );
    }

    public function getElemTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASREAD;

            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
        }

        return parent::getElemTarget($component, $props);
    }
    
    public function getElemStyles(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
                return array(
                    'display' => 'block'
                );

            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:
                return array(
                    'display' => 'none'
                );
        }

        return parent::getElemStyles($component, $props);
    }
}



