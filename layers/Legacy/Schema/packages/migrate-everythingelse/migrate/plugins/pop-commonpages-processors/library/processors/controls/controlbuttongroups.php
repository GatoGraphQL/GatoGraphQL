<?php

class GD_CommonPages_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ = 'customcontrolbuttongroup-addcontentfaq';
    public const MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ = 'customcontrolbuttongroup-accountfaq';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ],
            [self::class, self::MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_CustomAnchorControls::class, GD_CommonPages_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ];
                break;

            case self::MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_CustomAnchorControls::class, GD_CommonPages_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ];
                break;
        }
        
        return $ret;
    }
}


