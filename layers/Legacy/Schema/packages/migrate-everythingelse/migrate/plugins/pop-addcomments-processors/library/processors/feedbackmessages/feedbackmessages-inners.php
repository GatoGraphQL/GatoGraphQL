<?php

class PoP_Module_Processor_CommentsFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_ADDCOMMENT = 'feedbackmessageinner-addcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_ADDCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



