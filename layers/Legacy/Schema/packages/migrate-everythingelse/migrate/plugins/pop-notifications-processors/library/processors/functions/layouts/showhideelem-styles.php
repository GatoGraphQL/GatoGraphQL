<?php

class GD_AAL_Module_Processor_ShowHideElemStyleLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES = 'layout-marknotificationasread-showelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES = 'layout-marknotificationasread-hideelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES = 'layout-marknotificationasunread-showelemstyles';
    public final const COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES = 'layout-marknotificationasunread-hideelemstyles';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES,
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES,
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES,
            self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES,
        );
    }

    public function getElemTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASREAD;

            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
            case self::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:
                return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
        }

        return parent::getElemTarget($component, $props);
    }
    
    public function getElemStyles(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
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



