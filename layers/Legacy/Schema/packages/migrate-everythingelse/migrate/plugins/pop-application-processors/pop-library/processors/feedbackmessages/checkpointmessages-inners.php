<?php

class PoP_Application_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_DOMAIN = 'checkpointmessageinner-domain';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::class, PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



