<?php

class PoP_Core_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS = 'layout-feedbackmessagealert-inviteusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS => [PoP_Core_Module_Processor_FeedbackMessageLayouts::class, PoP_Core_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



