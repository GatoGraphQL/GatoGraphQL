<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION = 'checkpointmessage-profileorganization';
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL = 'checkpointmessage-profileindividual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            [self::class, self::COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION],
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



