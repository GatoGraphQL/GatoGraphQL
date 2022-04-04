<?php

class PoP_Module_Processor_ListCommentsFeedbackMessageInners extends PoP_Module_Processor_ListFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_COMMENTS = 'feedbackmessageinner-comments';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_COMMENTS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_COMMENTS => [PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



