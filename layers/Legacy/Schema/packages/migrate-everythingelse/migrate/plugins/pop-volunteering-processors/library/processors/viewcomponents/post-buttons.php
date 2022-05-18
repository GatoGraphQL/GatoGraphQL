<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPCore_GenericForms_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG = 'viewcomponent-compactpostbutton-volunteer-big';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG = 'viewcomponent-postbutton-volunteer-big';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY = 'viewcomponent-postbutton-volunteer-tiny';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY],
        );
    }

    // function headerShowUrl(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY => [PoP_Volunteering_Module_Processor_ViewComponentButtonInners::class, PoP_Volunteering_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                $ret .= 'btn btn-compact btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return TranslationAPIFacade::getInstance()->__('Volunteer!', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return 'volunteerURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }
}



