<?php

class PoP_Module_Processor_ProfileFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE = 'feedbackmessageinner-createprofile';
    public final const MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE = 'feedbackmessageinner-updateprofile';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            self::MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



