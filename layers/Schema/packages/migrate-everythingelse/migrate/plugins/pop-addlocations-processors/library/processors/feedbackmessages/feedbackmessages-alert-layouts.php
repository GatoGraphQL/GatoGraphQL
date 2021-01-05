<?php

class PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION = 'layout-feedbackmessagealert-createlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::class, PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



