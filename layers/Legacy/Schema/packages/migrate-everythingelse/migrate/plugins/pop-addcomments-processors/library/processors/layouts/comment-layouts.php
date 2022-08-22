<?php

class PoP_Module_Processor_CommentsLayouts extends PoP_Module_Processor_CommentLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENT_LIST = 'layout-comment-list';
    public final const COMPONENT_LAYOUT_COMMENT_ADD = 'layout-comment-add';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_COMMENT_LIST,
            self::COMPONENT_LAYOUT_COMMENT_ADD,
        );
    }

    public function isRuntimeAdded(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_COMMENT_ADD:
                return true;
        }

        return parent::isRuntimeAdded($component, $props);
    }
}



