<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserViewComponentButtons extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW = 'viewcomponent-userbutton-sendmessage-preview';
    public const MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL = 'viewcomponent-userbutton-sidebar-sendmessage-full';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL],
        );
    }

    // function headerShowUrl(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

    //             return true;
    //     }

    //     return parent::headerShowUrl($module);
    // }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW],
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Send message', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                $this->appendProp($module, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($module, $props);
    }
    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getUrlField(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return 'contactURL';
        }

        return parent::getUrlField($module);
    }
}



