<?php

class PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER = 'layout-feedbackmessagealert-newsletter';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION = 'layout-feedbackmessagealert-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTER],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



