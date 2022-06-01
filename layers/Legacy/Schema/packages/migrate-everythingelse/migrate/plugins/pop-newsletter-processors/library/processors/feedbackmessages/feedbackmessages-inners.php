<?php

class PoP_Newsletter_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER = 'feedbackmessageinner-newsletter';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION = 'feedbackmessageinner-newsletterunsubscription';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER,
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER],
            self::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



