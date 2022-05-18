<?php

class GD_AAL_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION = 'notifications-quicklinkgroup-notification';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION:
                $ret[] = [GD_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD];
                break;
        }

        return $ret;
    }
}


