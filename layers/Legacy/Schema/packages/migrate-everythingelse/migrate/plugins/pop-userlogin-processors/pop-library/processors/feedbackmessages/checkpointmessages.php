<?php

class GD_UserLogin_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_CHECKPOINTMESSAGE_NOTLOGGEDIN = 'checkpointmessage-notloggedin';
    public const MODULE_CHECKPOINTMESSAGE_LOGGEDIN = 'checkpointmessage-loggedin';
    public const MODULE_CHECKPOINTMESSAGE_LOGGEDINCANEDIT = 'checkpointmessage-loggedincanedit';
    public const MODULE_CHECKPOINTMESSAGE_LOGGEDINISADMIN = 'checkpointmessage-loggedinisadmin';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGE_NOTLOGGEDIN],
            [self::class, self::MODULE_CHECKPOINTMESSAGE_LOGGEDIN],
            [self::class, self::MODULE_CHECKPOINTMESSAGE_LOGGEDINCANEDIT],
            [self::class, self::MODULE_CHECKPOINTMESSAGE_LOGGEDINISADMIN],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CHECKPOINTMESSAGE_NOTLOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageInners::class, GD_UserLogin_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN],
            self::MODULE_CHECKPOINTMESSAGE_LOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageInners::class, GD_UserLogin_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDIN],
            self::MODULE_CHECKPOINTMESSAGE_LOGGEDINCANEDIT => [GD_UserLogin_Module_Processor_UserCheckpointMessageInners::class, GD_UserLogin_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT],
            self::MODULE_CHECKPOINTMESSAGE_LOGGEDINISADMIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageInners::class, GD_UserLogin_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



