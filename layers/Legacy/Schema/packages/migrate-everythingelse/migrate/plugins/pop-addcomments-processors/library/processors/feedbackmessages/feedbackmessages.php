<?php

class PoP_Module_Processor_CommentsFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_COMMENTS = 'feedbackmessage-comments';
    public final const MODULE_FEEDBACKMESSAGE_ADDCOMMENT = 'feedbackmessage-addcomment';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_COMMENTS],
            [self::class, self::MODULE_FEEDBACKMESSAGE_ADDCOMMENT],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_COMMENTS => [PoP_Module_Processor_ListCommentsFeedbackMessageInners::class, PoP_Module_Processor_ListCommentsFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_COMMENTS],
            self::MODULE_FEEDBACKMESSAGE_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageInners::class, PoP_Module_Processor_CommentsFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_ADDCOMMENT],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}



