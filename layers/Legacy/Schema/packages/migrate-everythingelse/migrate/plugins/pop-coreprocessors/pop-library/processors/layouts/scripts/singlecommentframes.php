<?php

class PoP_Module_Processor_SingleCommentFramesLayouts extends PoP_Module_Processor_SingleCommentScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENTFRAME_LIST = 'layout-commentframe-list';
    public final const COMPONENT_LAYOUT_COMMENTFRAME_ADD = 'layout-commentframe-add';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_COMMENTFRAME_LIST],
            [self::class, self::COMPONENT_LAYOUT_COMMENTFRAME_ADD],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_COMMENTFRAME_LIST:
                return [PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::COMPONENT_LAYOUT_COMMENT_LIST];

            case self::COMPONENT_LAYOUT_COMMENTFRAME_ADD:
                return [PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::COMPONENT_LAYOUT_COMMENT_ADD];
        }
        
        return parent::getLayoutSubmodule($component);
    }
}



