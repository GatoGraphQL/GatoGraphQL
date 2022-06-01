<?php

class GD_AAL_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION = 'notifications-quicklinkgroup-notification';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION:
                $ret[] = [GD_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD];
                break;
        }

        return $ret;
    }
}


