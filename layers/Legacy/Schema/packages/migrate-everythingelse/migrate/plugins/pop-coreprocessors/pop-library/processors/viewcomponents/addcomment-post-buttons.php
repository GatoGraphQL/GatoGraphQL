<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_AddCommentPostViewComponentButtons extends PoP_Module_Processor_AddCommentPostViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT = 'viewcomponent-postbutton-addcomment';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG = 'viewcomponent-postbutton-addcomment-big';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG],
        );
    }

    public function getTargetDynamicallyRenderedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubcomponents($component);
    }

    // function headerShowUrl(\PoP\ComponentModel\Component\Component $component) {

    //     switch ($component->name) {

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL],
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
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $ret .= 'btn btn-success btn-block btn-important';
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return 'addCommentURL';
        }

        return parent::getUrlField($component);
    }
    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



