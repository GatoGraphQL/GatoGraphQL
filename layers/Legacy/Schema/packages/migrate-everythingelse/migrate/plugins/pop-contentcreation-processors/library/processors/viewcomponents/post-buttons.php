<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA = 'viewcomponent-postbutton-flag-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN = 'viewcomponent-postbutton-flag-previewdropdown';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN],
        );
    }

    // function headerShowUrl(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN],
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
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-flag';
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return 'flagURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }
}



