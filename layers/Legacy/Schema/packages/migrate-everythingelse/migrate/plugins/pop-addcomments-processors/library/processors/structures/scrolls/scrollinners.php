<?php

class PoP_Module_Processor_CommentScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_COMMENTS_LIST = 'scrollinner-comments-list';
    public final const MODULE_SCROLLINNER_COMMENTS_ADD = 'scrollinner-comments-add';
    public final const MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS = 'layout-postcommentscroll-inner';
    public final const MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE = 'layout-postcommentscroll-inner-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_COMMENTS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_COMMENTS_ADD],
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS],
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_COMMENTS_LIST:
            case self::COMPONENT_SCROLLINNER_COMMENTS_ADD:
            case self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS:
            case self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_COMMENTS_LIST:
            case self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_SingleCommentFramesLayouts::class, PoP_Module_Processor_SingleCommentFramesLayouts::COMPONENT_LAYOUT_COMMENTFRAME_LIST];
                break;

            case self::COMPONENT_SCROLLINNER_COMMENTS_ADD:
                $ret[] = [PoP_Module_Processor_SingleCommentFramesLayouts::class, PoP_Module_Processor_SingleCommentFramesLayouts::COMPONENT_LAYOUT_COMMENTFRAME_ADD];
                break;

            case self::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


