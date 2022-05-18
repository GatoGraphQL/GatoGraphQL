<?php

class PoP_Module_Processor_ListCommentsFeedbackMessageInners extends PoP_Module_Processor_ListFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS = 'feedbackmessageinner-comments';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS => [PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



