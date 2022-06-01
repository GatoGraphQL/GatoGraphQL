<?php

class GD_URE_AAL_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY = 'ure-aal-quicklinkgroup-user-joinedcommunity';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP];
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }
}


