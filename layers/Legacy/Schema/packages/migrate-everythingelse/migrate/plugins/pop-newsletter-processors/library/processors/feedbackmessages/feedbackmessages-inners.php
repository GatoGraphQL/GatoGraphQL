<?php

class PoP_Newsletter_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER = 'feedbackmessageinner-newsletter';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION = 'feedbackmessageinner-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER],
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



