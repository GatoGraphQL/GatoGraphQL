<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA = 'viewcomponent-postbutton-flag-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN = 'viewcomponent-postbutton-flag-previewdropdown';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN],
        );
    }

    // function headerShowUrl(array $componentVariation) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

    //             return true;
    //     }

    //     return parent::headerShowUrl($componentVariation);
    // }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-flag';
                break;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return 'flagURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }
}



