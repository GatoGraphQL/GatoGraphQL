<?php

class PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_FLAG = 'layout-feedbackmessagealert-flag';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT = 'layout-feedbackmessagealert-createcontent';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT = 'layout-feedbackmessagealert-updatecontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_FLAG],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_FLAG => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_FLAG],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($componentVariation);
    }
}



