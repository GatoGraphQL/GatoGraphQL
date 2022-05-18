<?php

class PoP_Volunteering_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_VOLUNTEER = 'feedbackmessageinner-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_VOLUNTEER],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_VOLUNTEER => [PoP_Volunteering_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Volunteering_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_VOLUNTEER],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



