<?php

class PoP_Module_Processor_CommentsContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENT_COMMENTSINGLE = 'content-commentsingle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENT_COMMENTSINGLE],
        );
    }
    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENT_COMMENTSINGLE:
                return [PoP_Module_Processor_CommentContentInners::class, PoP_Module_Processor_CommentContentInners::COMPONENT_CONTENTINNER_COMMENTSINGLE];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENT_COMMENTSINGLE:
                $this->appendProp($component, $props, 'class', 'well well-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


