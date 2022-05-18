<?php

class PoP_Application_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_CHECKPOINTMESSAGE_DOMAIN = 'checkpointmessage-domain';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGE_DOMAIN],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_CHECKPOINTMESSAGE_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageInners::class, PoP_Application_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_DOMAIN],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}



