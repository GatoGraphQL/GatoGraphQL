<?php

class PoP_Module_Processor_ProfileFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public const MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE = 'feedbackmessageinner-createprofile';
    public const MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE = 'feedbackmessageinner-updateprofile';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            self::MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );

        if ($layout = $layouts[$module[1]]) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



