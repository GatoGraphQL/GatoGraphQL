<?php

class GD_URE_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE = 'ure-controlbuttongroup-contentsource';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY];
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER];
                break;
        }
        
        return $ret;
    }
}


