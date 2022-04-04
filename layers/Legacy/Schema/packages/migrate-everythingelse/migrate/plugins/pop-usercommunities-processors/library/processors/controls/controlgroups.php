<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class GD_URE_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_URE_CONTROLGROUP_CONTENTSOURCE = 'ure-controlgroup-contentsource';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTROLGROUP_CONTENTSOURCE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_URE_CONTROLGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE];
                break;
        }

        return $ret;
    }
}


