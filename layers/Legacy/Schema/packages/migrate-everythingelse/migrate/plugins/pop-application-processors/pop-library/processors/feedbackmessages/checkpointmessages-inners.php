<?php

class PoP_Application_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_DOMAIN = 'checkpointmessageinner-domain';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::class, PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



