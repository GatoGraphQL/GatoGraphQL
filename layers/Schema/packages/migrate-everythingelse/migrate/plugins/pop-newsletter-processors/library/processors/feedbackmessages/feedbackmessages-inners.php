<?php

class PoP_Newsletter_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public const MODULE_FEEDBACKMESSAGEINNER_NEWSLETTER = 'feedbackmessageinner-newsletter';
    public const MODULE_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION = 'feedbackmessageinner-newsletterunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTER],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER],
            self::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



