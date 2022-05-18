<?php

class PoP_Module_Processor_SettingsFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS = 'layout-feedbackmessagealert-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS => [PoP_Module_Processor_SettingsFeedbackMessageLayouts::class, PoP_Module_Processor_SettingsFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_SETTINGS],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



