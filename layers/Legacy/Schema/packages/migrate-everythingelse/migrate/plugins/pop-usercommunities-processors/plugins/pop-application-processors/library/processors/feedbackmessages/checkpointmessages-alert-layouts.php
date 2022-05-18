<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY = 'layout-checkpointmessagealert-profilecommunity';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP = 'layout-checkpointmessagealert-profilecommunityeditmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY],
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



