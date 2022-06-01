<?php

class GD_UserCommunities_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY = 'checkpointmessageinner-profilecommunity';
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP = 'checkpointmessageinner-profilecommunityeditmembership';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITY,
            self::COMPONENT_CHECKPOINTMESSAGEINNER_PROFILECOMMUNITYEDITMEMBERSHIP,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
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



