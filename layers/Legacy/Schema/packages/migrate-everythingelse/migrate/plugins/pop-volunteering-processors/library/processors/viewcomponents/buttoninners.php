<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL = 'viewcomponent-buttoninner-volunteer-full';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-volunteer-previewdropdown';
    public final const COMPONENT_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG = 'viewcomponent-compactbuttoninner-volunteer-big';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN,
            self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG,
        );
    }
    
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:
                return 'fa-fw fa-leaf';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Volunteer!', 'pop-coreprocessors');

            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:
                return TranslationAPIFacade::getInstance()->__('Click to Volunteer', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


