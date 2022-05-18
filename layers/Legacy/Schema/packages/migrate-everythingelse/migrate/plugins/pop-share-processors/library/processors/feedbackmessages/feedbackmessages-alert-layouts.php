<?php

class PoP_Share_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL = 'layout-feedbackmessagealert-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageLayouts::class, PoP_Share_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_SHAREBYEMAIL],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



