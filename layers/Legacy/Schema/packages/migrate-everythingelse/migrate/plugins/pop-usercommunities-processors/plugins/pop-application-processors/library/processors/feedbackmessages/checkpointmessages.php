<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITY = 'checkpointmessage-profilecommunity';
    public final const MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessage-profilecommunityeditmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITY],
            [self::class, self::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY],
            self::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageInners::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



