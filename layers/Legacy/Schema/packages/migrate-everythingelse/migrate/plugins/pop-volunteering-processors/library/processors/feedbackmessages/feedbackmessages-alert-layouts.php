<?php

class PoP_Volunteering_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_VOLUNTEER = 'layout-feedbackmessagealert-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_VOLUNTEER],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_VOLUNTEER => [PoP_Volunteering_Module_Processor_FeedbackMessageLayouts::class, PoP_Volunteering_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



