<?php

class PoP_Application_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_CHECKPOINTMESSAGE_DOMAIN = 'checkpointmessage-domain';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGE_DOMAIN],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CHECKPOINTMESSAGE_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageInners::class, PoP_Application_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



