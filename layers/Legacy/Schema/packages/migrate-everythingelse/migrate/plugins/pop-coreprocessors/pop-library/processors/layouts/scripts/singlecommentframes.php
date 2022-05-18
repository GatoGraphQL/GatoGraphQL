<?php

class PoP_Module_Processor_SingleCommentFramesLayouts extends PoP_Module_Processor_SingleCommentScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_COMMENTFRAME_LIST = 'layout-commentframe-list';
    public final const MODULE_LAYOUT_COMMENTFRAME_ADD = 'layout-commentframe-add';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_COMMENTFRAME_LIST],
            [self::class, self::MODULE_LAYOUT_COMMENTFRAME_ADD],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_COMMENTFRAME_LIST:
                return [PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST];

            case self::MODULE_LAYOUT_COMMENTFRAME_ADD:
                return [PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_ADD];
        }
        
        return parent::getLayoutSubmodule($module);
    }
}



