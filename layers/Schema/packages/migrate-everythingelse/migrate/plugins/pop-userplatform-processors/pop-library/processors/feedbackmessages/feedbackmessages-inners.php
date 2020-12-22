<?php

class PoP_Core_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public const MODULE_FEEDBACKMESSAGEINNER_INVITENEWUSERS = 'feedbackmessageinner-inviteusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_INVITENEWUSERS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_INVITENEWUSERS => [PoP_Core_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Core_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS],
        );

        if ($layout = $layouts[$module[1]]) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



