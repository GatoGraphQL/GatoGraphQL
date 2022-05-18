<?php

class PoP_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_MYPREFERENCES = 'feedbackmessageinner-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_MYPREFERENCES],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageAlertLayouts::class, PoP_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



