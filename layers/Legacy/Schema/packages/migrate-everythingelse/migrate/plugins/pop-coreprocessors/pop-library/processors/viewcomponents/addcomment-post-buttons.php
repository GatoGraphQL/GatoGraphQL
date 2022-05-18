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

    public function getTargetDynamicallyRenderedSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($componentVariation);
    }

    // function headerShowUrl(array $componentVariation) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

    //             return true;
    //     }

    //     return parent::headerShowUrl($componentVariation);
    // }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL],
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
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $ret .= 'btn btn-success btn-block btn-important';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return 'addCommentURL';
        }

        return parent::getUrlField($componentVariation);
    }
    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
                $this->appendProp($componentVariation, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



