<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY = 'checkpointmessageinner-profilecommunity';
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessageinner-profilecommunityeditmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY],
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



