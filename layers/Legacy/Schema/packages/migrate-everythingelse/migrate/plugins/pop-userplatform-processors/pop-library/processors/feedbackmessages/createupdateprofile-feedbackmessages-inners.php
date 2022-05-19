<?php

class PoP_Module_Processor_ProfileFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE = 'feedbackmessageinner-createprofile';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE = 'feedbackmessageinner-updateprofile';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



