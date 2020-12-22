<?php

class GD_URE_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS = 'controlbuttongroup-invitenewmembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS];
                break;
        }
        
        return $ret;
    }
}


