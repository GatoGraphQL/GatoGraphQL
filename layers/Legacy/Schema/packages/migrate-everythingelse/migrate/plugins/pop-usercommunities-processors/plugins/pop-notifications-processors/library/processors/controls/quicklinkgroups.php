<?php

class GD_URE_AAL_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY = 'ure-aal-quicklinkgroup-user-joinedcommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP];
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }
}


