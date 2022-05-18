<?php

class PoP_Module_Processor_CommentsFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_COMMENTS = 'feedbackmessage-comments';
    public final const MODULE_FEEDBACKMESSAGE_ADDCOMMENT = 'feedbackmessage-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_COMMENTS],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_ADDCOMMENT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_COMMENTS => [PoP_Module_Processor_ListCommentsFeedbackMessageInners::class, PoP_Module_Processor_ListCommentsFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS],
            self::COMPONENT_FEEDBACKMESSAGE_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageInners::class, PoP_Module_Processor_CommentsFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_ADDCOMMENT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



