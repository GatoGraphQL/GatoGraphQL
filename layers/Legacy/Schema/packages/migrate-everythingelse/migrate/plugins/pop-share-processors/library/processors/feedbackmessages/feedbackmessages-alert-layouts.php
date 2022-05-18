<?php

class PoP_Share_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL = 'layout-feedbackmessagealert-sharebyemail';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageLayouts::class, PoP_Share_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_SHAREBYEMAIL],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($componentVariation);
    }
}



