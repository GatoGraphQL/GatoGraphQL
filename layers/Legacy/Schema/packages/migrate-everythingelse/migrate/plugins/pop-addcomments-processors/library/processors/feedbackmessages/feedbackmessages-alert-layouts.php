<?php

class PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS = 'layout-feedbackmessagealert-comments';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT = 'layout-feedbackmessagealert-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS => [PoP_Module_Processor_CommentsFeedbackMessageLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_COMMENTS],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



