<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public const MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA = 'viewcomponent-postbutton-flag-socialmedia';
    public const MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN = 'viewcomponent-postbutton-flag-previewdropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN],
        );
    }

    // function headerShowUrl(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

    //             return true;
    //     }

    //     return parent::headerShowUrl($module);
    // }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN],
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
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-flag';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($module, $props);
    }
    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return 'flagURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }
}



