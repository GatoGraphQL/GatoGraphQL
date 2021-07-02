<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL = 'viewcomponent-buttoninner-volunteer-full';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-volunteer-previewdropdown';
    public const MODULE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG = 'viewcomponent-compactbuttoninner-volunteer-big';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN],
            [self::class, self::MODULE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG],
        );
    }
    
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:
                return 'fa-fw fa-leaf';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Volunteer!', 'pop-coreprocessors');

            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:
                return TranslationAPIFacade::getInstance()->__('Click to Volunteer', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($module);
    }
}


