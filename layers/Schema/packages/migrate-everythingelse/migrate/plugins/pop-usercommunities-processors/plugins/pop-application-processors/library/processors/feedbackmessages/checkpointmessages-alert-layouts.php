<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY = 'layout-checkpointmessagealert-profilecommunity';
    public const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP = 'layout-checkpointmessagealert-profilecommunityeditmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY],
            self::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($layout = $layouts[$module[1]]) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



