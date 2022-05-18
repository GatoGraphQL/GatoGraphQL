<?php

class PoP_Module_Processor_CommentsFramesLayouts extends PoP_Module_Processor_CommentsScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT = 'layout-comments-appendtoscript';
    public final const MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT = 'layout-commentsempty-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT],
            [self::class, self::MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($module);
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT:
            case self::MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
        }
        
        return parent::getLayoutSubmodule($module);
    }
}



