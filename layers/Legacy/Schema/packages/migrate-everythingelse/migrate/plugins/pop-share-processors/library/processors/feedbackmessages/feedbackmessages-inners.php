<?php

class PoP_Share_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_SHAREBYEMAIL = 'feedbackmessageinner-sharebyemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_SHAREBYEMAIL],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Share_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



