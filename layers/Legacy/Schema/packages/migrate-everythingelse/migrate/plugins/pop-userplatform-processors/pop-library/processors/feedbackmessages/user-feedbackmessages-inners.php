<?php

class PoP_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES = 'feedbackmessageinner-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageAlertLayouts::class, PoP_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



