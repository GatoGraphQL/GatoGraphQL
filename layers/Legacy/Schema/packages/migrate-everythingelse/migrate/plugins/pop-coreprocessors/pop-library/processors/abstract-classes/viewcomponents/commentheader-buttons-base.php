<?php

abstract class PoP_Module_Processor_CommentHeaderViewComponentButtonsBase extends PoP_Module_Processor_CommentViewComponentButtonsBase
{
    public function getHeaderSubmodule(array $component): ?array
    {
        return [PoP_Module_Processor_ReplyCommentViewComponentHeaders::class, PoP_Module_Processor_ReplyCommentViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL];
    }
}
