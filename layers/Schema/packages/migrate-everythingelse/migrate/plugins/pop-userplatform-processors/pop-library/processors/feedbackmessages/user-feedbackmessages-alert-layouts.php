<?php

class PoP_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES = 'layout-feedbackmessagealert-mypreferences';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageLayouts::class, PoP_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



