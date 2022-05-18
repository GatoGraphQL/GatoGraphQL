<?php

class PoP_Application_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_CHECKPOINTMESSAGE_DOMAIN = 'checkpointmessage-domain';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CHECKPOINTMESSAGE_DOMAIN],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_CHECKPOINTMESSAGE_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageInners::class, PoP_Application_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_DOMAIN],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



