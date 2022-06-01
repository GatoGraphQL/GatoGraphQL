<?php

class PoP_Module_Processor_CommentsFramesLayouts extends PoP_Module_Processor_CommentsScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT = 'layout-comments-appendtoscript';
    public final const COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT = 'layout-commentsempty-appendtoscript';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT,
            self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT,
        );
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT:
            case self::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
        }
        
        return parent::getLayoutSubcomponent($component);
    }
}



