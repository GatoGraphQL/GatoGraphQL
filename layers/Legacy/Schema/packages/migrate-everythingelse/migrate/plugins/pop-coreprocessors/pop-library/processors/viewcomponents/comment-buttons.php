<?php

class PoP_Module_Processor_CommentViewComponentButtons extends PoP_Module_Processor_CommentViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY = 'viewcomponent-commentbutton-reply';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY],
        );
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return array(
                    [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($module);
    }

    public function getTargetDynamicallyRenderedSubcomponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return array(
                    'customPost' => array(
                        [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                    ),
                );
        }

        return parent::getTargetDynamicallyRenderedSubcomponentSubmodules($module);
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT]
        );

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return $buttoninners[$module[1]];
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $ret .= ' btn btn-link btn-xs';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return 'replycommentURL';
        }

        return parent::getUrlField($module);
    }
    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $this->appendProp($module, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



