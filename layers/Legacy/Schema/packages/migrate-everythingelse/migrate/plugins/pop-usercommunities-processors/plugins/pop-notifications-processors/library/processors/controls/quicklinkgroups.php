<?php

class GD_URE_AAL_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY = 'ure-aal-quicklinkgroup-user-joinedcommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::MODULE_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP];
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::MODULE_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }
}


