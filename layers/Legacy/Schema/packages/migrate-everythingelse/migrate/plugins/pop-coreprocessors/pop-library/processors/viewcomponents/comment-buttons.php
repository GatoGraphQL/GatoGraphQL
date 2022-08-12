<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

class PoP_Module_Processor_CommentViewComponentButtons extends PoP_Module_Processor_CommentViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY = 'viewcomponent-commentbutton-reply';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY,
        );
    }

    public function getTargetDynamicallyRenderedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return array(
                    [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT],
                );
        }

        return parent::getTargetDynamicallyRenderedSubcomponents($component);
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getTargetDynamicallyRenderedSubcomponentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return [
                    new RelationalComponentFieldNode(
                        new LeafField(
                            'customPost',
                            null,
                            [],
                            [],
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                        [
                            [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                        ],
                    )
                ];
        }

        return parent::getTargetDynamicallyRenderedSubcomponentSubcomponents($component);
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY => [PoP_Module_Processor_ViewComponentButtonInners::class, PoP_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT]
        );

        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return $buttoninners[$component->name];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $ret .= ' btn btn-link btn-xs';
                break;
        }

        return $ret;
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return 'replycommentURL';
        }

        return parent::getUrlField($component);
    }
    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
                $this->appendProp($component, $props, 'class', 'pop-hidden-print');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



