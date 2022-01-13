<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class CommonPages_EM_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_CONTROLBUTTONGROUP_ADDLOCATIONPOST = 'customcontrolbuttongroup-addlocationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ADDLOCATIONPOST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_ADDLOCATIONPOST:
                $ret[] = [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST];
                $ret = \PoP\Root\App::getHookManager()->applyFilters(
                    'CommonPages_EM_Module_Processor_ControlButtonGroups:modules',
                    $ret,
                    $module,
                    $props
                );
                break;
        }
        
        return $ret;
    }
}


