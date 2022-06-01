<?php

class PoP_Module_Processor_CommentsLayouts extends PoP_Module_Processor_CommentLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENT_LIST = 'layout-comment-list';
    public final const COMPONENT_LAYOUT_COMMENT_ADD = 'layout-comment-add';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_COMMENT_LIST],
            [self::class, self::COMPONENT_LAYOUT_COMMENT_ADD],
        );
    }

    public function isRuntimeAdded(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_COMMENT_ADD:
                return true;
        }

        return parent::isRuntimeAdded($component, $props);
    }
}



