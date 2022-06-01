<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY = 'checkpointmessageinner-profilecommunity';
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessageinner-profilecommunityeditmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY],
            [self::class, self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITY],
            self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP => [GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILECOMMUNITYEDITMEMBERSHIP],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



