<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserViewComponentButtons extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW = 'viewcomponent-userbutton-sendmessage-preview';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL = 'viewcomponent-userbutton-sidebar-sendmessage-full';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW,
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL,
        );
    }

    // function headerShowUrl(\PoP\ComponentModel\Component\Component $component) {

    //     switch ($component->name) {

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL => [PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::class, PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Send message', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                // $ret .= 'btn btn-sm btn-success btn-block btn-important';
                $ret .= 'btn btn-info btn-block btn-important';
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
                $ret .= 'btn btn-compact btn-link';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
                return 'contactURL';
        }

        return parent::getUrlField($component);
    }
}



