<?php

class PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_FLAG = 'layout-feedbackmessagealert-flag';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT = 'layout-feedbackmessagealert-createcontent';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT = 'layout-feedbackmessagealert-updatecontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_FLAG],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_FLAG => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_FLAG],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



