<?php

class PoP_Module_Processor_ReplyCommentViewComponentHeaders extends PoP_Module_Processor_ReplyCommentViewComponentHeadersBase
{
    public final const COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT = 'viewcomponent-header-replycomment';
    public final const COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL = 'viewcomponent-header-replycomment-url';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT],
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL],
        );
    }

    public function getPostSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT:
                return [PoP_Module_Processor_CommentViewComponentHeaders::class, PoP_Module_Processor_CommentViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST];
        
            case self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL:
                return [PoP_Module_Processor_CommentViewComponentHeaders::class, PoP_Module_Processor_CommentViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST_URL];
        }
        
        return parent::getPostSubcomponent($component);
    }

    public function getCommentSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL:
                return [PoP_Module_Processor_CommentClippedViewComponentHeaders::class, PoP_Module_Processor_CommentClippedViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTCLIPPED];
        }
        
        return parent::getCommentSubcomponent($component);
    }
}


