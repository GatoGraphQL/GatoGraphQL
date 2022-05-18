<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_AddCommentPostViewComponentButtons extends PoP_Module_Processor_AddCommentPostViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT = 'viewcomponent-postbutton-addcomment';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG = 'viewcomponent-postbutton-addcomment-big';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG],
        );
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($module);
    }

    // function headerShowUrl(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

    //             return true;
    //     }

    //     return parent::headerShowUrl($module);
    // }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL],
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
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $ret .= 'btn btn-success btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return 'addCommentURL';
        }

        return parent::getUrlField($module);
    }
    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $this->appendProp($module, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



