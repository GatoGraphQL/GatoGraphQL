<?php

class PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST = 'controlbuttongroup-addrelatedpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST:
                $ret[] = [PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::class, PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST];
                break;
        }
        
        return $ret;
    }
}


