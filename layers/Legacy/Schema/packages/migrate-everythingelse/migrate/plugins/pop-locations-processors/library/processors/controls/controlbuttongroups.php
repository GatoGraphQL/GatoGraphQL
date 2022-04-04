<?php

class PoP_Locations_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLEMAP = 'controlbuttongroup-togglemap';
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP = 'controlbuttongroup-toggleauthormap';
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP = 'controlbuttongroup-toggletagmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_TOGGLEMAP],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_TOGGLEMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLEMAP];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLETAGMAP];
                break;
        }
        
        return $ret;
    }
}


