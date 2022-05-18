<?php

class PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE = 'layout-feedbackmessagealert-createprofile';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE = 'layout-feedbackmessagealert-updateprofile';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE => [PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE => [PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



