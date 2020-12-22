<?php

class GD_URE_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public const MODULE_CONTROLGROUP_MYMEMBERS = 'controlgroup-mymembers';
    public const MODULE_CONTROLGROUP_MYBLOCKMEMBERS = 'controlgroup-myblockmembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_MYMEMBERS],
            [self::class, self::MODULE_CONTROLGROUP_MYBLOCKMEMBERS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_MYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_CONTROLGROUP_MYBLOCKMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


