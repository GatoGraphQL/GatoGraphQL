<?php

class PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION = 'layout-feedbackmessagealert-createlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::class, PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($componentVariation);
    }
}



