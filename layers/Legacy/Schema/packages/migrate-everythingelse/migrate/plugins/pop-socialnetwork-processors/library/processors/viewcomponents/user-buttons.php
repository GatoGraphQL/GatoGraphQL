<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserViewComponentButtons extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW = 'viewcomponent-userbutton-sendmessage-preview';
    public final const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL = 'viewcomponent-userbutton-sidebar-sendmessage-full';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL],
        );
    }

    // function headerShowUrl(array $componentVariation) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

    //             return true;
    //     }

    //     return parent::headerShowUrl($componentVariation);
    // }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW],
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Send message', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                $this->appendProp($componentVariation, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getUrlField(array $componentVariation)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return 'contactURL';
        }

        return parent::getUrlField($componentVariation);
    }
}



