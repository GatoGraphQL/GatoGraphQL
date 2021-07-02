<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Locations_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public const MODULE_CONTROLGROUP_BLOCKMAPPOSTLIST = 'controlgroup-blockmappostlist';
    public const MODULE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST = 'controlgroup-blockauthormappostlist';
    public const MODULE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST = 'controlgroup-blocktagmappostlist';
    public const MODULE_CONTROLGROUP_BLOCKMAPUSERLIST = 'controlgroup-blockmapuserlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_BLOCKMAPPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKMAPUSERLIST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_BLOCKMAPPOSTLIST:
            case self::MODULE_CONTROLGROUP_BLOCKMAPUSERLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::MODULE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::MODULE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
        }

        return $ret;
    }
}


