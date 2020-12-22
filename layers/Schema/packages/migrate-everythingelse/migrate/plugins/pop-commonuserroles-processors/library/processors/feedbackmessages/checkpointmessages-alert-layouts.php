<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION = 'layout-checkpointmessagealert-profileorganization';
    public const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL = 'layout-checkpointmessagealert-profileindividual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );

        if ($layout = $layouts[$module[1]]) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



