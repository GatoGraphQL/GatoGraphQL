<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITY = 'checkpointmessage-profilecommunity';
    public final const COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessage-profilecommunityeditmembership';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITY,
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY],
            self::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



