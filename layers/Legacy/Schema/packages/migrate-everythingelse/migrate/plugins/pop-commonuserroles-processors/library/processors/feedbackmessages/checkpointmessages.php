<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION = 'checkpointmessage-profileorganization';
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL = 'checkpointmessage-profileindividual';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION,
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION],
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



