<?php

class PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE = 'layout-feedbackmessagealert-createprofile';
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE = 'layout-feedbackmessagealert-updateprofile';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE => [PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_CreateProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE => [PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::class, PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



