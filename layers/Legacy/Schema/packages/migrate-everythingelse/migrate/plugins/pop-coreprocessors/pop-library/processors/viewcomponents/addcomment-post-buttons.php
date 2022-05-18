<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_AddCommentPostViewComponentButtons extends PoP_Module_Processor_AddCommentPostViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT = 'viewcomponent-postbutton-addcomment';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG = 'viewcomponent-postbutton-addcomment-big';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG],
        );
    }

    public function getTargetDynamicallyRenderedSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($component);
    }

    // function headerShowUrl(array $component) {

    //     switch ($component[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

    //             return true;
    //     }

    //     return parent::headerShowUrl($component);
    // }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL],
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
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $ret .= 'btn btn-success btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return 'addCommentURL';
        }

        return parent::getUrlField($component);
    }
    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



