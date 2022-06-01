<?php

class PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE = 'layout-feedbackmessagealert-createprofile';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE = 'layout-feedbackmessagealert-updateprofile';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE,
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE => [PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE => [PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



