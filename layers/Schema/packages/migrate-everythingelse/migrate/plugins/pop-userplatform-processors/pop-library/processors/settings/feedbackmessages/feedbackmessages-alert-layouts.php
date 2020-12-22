<?php

class PoP_Module_Processor_SettingsFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS = 'layout-feedbackmessagealert-settings';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS => [PoP_Module_Processor_SettingsFeedbackMessageLayouts::class, PoP_Module_Processor_SettingsFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_SETTINGS],
        );

        if ($layout = $layouts[$module[1]]) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



