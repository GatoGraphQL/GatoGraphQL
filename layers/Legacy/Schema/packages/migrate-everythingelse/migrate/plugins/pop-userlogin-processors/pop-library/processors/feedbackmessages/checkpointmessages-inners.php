<?php

class GD_UserLogin_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN = 'checkpointmessageinner-notloggedin';
    public final const MODULE_CHECKPOINTMESSAGEINNER_LOGGEDIN = 'checkpointmessageinner-loggedin';
    public final const MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT = 'checkpointmessageinner-loggedincanedit';
    public final const MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN = 'checkpointmessageinner-loggedinisadmin';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDIN],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_NOTLOGGEDIN],
            self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN],
            self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINCANEDIT],
            self::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINISADMIN],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



