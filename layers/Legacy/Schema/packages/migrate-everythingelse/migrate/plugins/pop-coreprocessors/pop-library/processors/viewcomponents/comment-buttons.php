<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

class PoP_Module_Processor_CommentViewComponentButtons extends PoP_Module_Processor_CommentViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY = 'viewcomponent-commentbutton-reply';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY],
        );
    }

    public function getTargetDynamicallyRenderedSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return array(
                    [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($component);
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getTargetDynamicallyRenderedSubcomponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return [
                    new RelationalModuleField(
                        'customPost',
                        [
                            [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                        ],
                    )
                ];
        }

        return parent::getTargetDynamicallyRenderedSubcomponentSubmodules($component);
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT]
        );

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return $buttoninners[$component[1]];
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $ret .= ' btn btn-link btn-xs';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return 'replycommentURL';
        }

        return parent::getUrlField($component);
    }
    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



