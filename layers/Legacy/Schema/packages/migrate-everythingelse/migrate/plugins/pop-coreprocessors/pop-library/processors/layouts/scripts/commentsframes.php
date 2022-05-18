<?php

class PoP_Module_Processor_CommentsFramesLayouts extends PoP_Module_Processor_CommentsScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT = 'layout-comments-appendtoscript';
    public final const COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT = 'layout-commentsempty-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT],
            [self::class, self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT:
            case self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
        }
        
        return parent::getLayoutSubmodule($component);
    }
}



