<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_QUICKLINKGROUP_EVENTBOTTOM = 'quicklinkgroup-automatedemails-eventbottom';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKGROUP_EVENTBOTTOM],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        switch ($module[1]) {
            case self::MODULE_QUICKLINKGROUP_EVENTBOTTOM:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                $ret[] = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS];
                break;
        }

        return $ret;
    }
}


