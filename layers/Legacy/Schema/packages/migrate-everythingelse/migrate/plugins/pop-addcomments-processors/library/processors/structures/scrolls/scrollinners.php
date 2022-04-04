<?php

class PoP_Module_Processor_CommentScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_COMMENTS_LIST = 'scrollinner-comments-list';
    public final const MODULE_SCROLLINNER_COMMENTS_ADD = 'scrollinner-comments-add';
    public final const MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS = 'layout-postcommentscroll-inner';
    public final const MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE = 'layout-postcommentscroll-inner-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_COMMENTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_COMMENTS_ADD],
            [self::class, self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS],
            [self::class, self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_COMMENTS_LIST:
            case self::MODULE_SCROLLINNER_COMMENTS_ADD:
            case self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS:
            case self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_COMMENTS_LIST:
            case self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_SingleCommentFramesLayouts::class, PoP_Module_Processor_SingleCommentFramesLayouts::MODULE_LAYOUT_COMMENTFRAME_LIST];
                break;

            case self::MODULE_SCROLLINNER_COMMENTS_ADD:
                $ret[] = [PoP_Module_Processor_SingleCommentFramesLayouts::class, PoP_Module_Processor_SingleCommentFramesLayouts::MODULE_LAYOUT_COMMENTFRAME_ADD];
                break;

            case self::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


