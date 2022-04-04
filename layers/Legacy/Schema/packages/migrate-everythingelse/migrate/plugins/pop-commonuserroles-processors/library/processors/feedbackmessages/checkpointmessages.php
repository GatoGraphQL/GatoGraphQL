<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_CHECKPOINTMESSAGE_PROFILEORGANIZATION = 'checkpointmessage-profileorganization';
    public final const MODULE_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL = 'checkpointmessage-profileindividual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            [self::class, self::MODULE_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CHECKPOINTMESSAGE_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION],
            self::MODULE_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



