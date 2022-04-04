<?php

class PoP_Module_Processor_CommentsLayouts extends PoP_Module_Processor_CommentLayoutsBase
{
    public final const MODULE_LAYOUT_COMMENT_LIST = 'layout-comment-list';
    public final const MODULE_LAYOUT_COMMENT_ADD = 'layout-comment-add';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_COMMENT_LIST],
            [self::class, self::MODULE_LAYOUT_COMMENT_ADD],
        );
    }

    public function isRuntimeAdded(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_COMMENT_ADD:
                return true;
        }

        return parent::isRuntimeAdded($module, $props);
    }
}



