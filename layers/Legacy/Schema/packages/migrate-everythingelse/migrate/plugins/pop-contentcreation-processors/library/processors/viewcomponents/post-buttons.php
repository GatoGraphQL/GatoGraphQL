<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA = 'viewcomponent-postbutton-flag-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN = 'viewcomponent-postbutton-flag-previewdropdown';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA,
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN,
        );
    }

    // function headerShowUrl(\PoP\ComponentModel\Component\Component $component) {

    //     switch ($component->name) {

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN => [PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::class, PoP_ContentCreation_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-flag';
                break;
        }

        return $ret;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return 'flagURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }
}



