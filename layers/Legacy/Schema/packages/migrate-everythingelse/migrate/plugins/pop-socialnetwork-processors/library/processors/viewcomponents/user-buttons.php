<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserViewComponentButtons extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW = 'viewcomponent-userbutton-sendmessage-preview';
    public final const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL = 'viewcomponent-userbutton-sidebar-sendmessage-full';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL],
        );
    }

    // function headerShowUrl(array $component) {

    //     switch ($component[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW],
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Send message', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                // $ret .= 'btn btn-sm btn-success btn-block btn-important';
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                $ret .= 'btn btn-compact btn-link';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getUrlField(array $component)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return 'contactURL';
        }

        return parent::getUrlField($component);
    }
}



