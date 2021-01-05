<?php

class PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN = 'layout-checkpointmessagealert-domain';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageLayouts::class, PoP_Application_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_DOMAIN],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



