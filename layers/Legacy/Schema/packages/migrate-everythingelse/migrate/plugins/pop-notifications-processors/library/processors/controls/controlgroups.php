<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class AAL_PoPProcessors_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_AAL_CONTROLGROUP_NOTIFICATIONLIST = 'controlgroup-notificationlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_CONTROLGROUP_NOTIFICATIONLIST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_AAL_CONTROLGROUP_NOTIFICATIONLIST:
                $ret[] = [AAL_PoPProcessors_Module_Processor_ControlButtonGroups::class, AAL_PoPProcessors_Module_Processor_ControlButtonGroups::MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_LOADLATESTBLOCK];
                break;
        }

        return $ret;
    }
}


