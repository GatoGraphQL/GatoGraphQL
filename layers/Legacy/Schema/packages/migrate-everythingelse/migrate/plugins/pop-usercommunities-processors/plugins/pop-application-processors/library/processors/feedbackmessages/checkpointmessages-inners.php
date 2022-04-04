<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY = 'checkpointmessageinner-profilecommunity';
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessageinner-profilecommunityeditmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY],
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



