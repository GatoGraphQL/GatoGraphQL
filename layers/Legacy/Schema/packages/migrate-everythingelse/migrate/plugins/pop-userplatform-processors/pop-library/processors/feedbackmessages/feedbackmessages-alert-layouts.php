<?php

class PoP_Core_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS = 'layout-feedbackmessagealert-inviteusers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWUSERS => [PoP_Core_Module_Processor_FeedbackMessageLayouts::class, PoP_Core_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($componentVariation);
    }
}



