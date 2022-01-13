<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPCore_GenericForms_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public const MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG = 'viewcomponent-compactpostbutton-volunteer-big';
    public const MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG = 'viewcomponent-postbutton-volunteer-big';
    public const MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY = 'viewcomponent-postbutton-volunteer-tiny';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY],
        );
    }

    // function headerShowUrl(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

    //             return true;
    //     }

    //     return parent::headerShowUrl($module);
    // }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                $ret .= 'btn btn-compact btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return TranslationAPIFacade::getInstance()->__('Volunteer!', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return 'volunteerURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }
}



