<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

class PoP_Module_Processor_CommentViewComponentButtons extends PoP_Module_Processor_CommentViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY = 'viewcomponent-commentbutton-reply';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY],
        );
    }

    public function getTargetDynamicallyRenderedSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return array(
                    [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($componentVariation);
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getTargetDynamicallyRenderedSubcomponentSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return [
                    new RelationalModuleField(
                        'customPost',
                        [
                            [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                        ],
                    )
                ];
        }

        return parent::getTargetDynamicallyRenderedSubcomponentSubmodules($componentVariation);
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT]
        );

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return $buttoninners[$componentVariation[1]];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $ret .= ' btn btn-link btn-xs';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return 'replycommentURL';
        }

        return parent::getUrlField($componentVariation);
    }
    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $this->appendProp($componentVariation, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



