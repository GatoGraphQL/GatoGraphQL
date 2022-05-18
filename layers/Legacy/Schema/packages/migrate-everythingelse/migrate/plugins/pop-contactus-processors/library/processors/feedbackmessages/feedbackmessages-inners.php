<?php

class PoP_ContactUs_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_CONTACTUS = 'feedbackmessageinner-contactus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_CONTACTUS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_CONTACTUS => [PoP_ContactUs_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContactUs_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUS],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



