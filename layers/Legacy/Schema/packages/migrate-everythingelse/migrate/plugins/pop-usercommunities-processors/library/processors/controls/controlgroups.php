<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_URE_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_URE_CONTROLGROUP_CONTENTSOURCE = 'ure-controlgroup-contentsource';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTROLGROUP_CONTENTSOURCE],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_URE_CONTROLGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE];
                break;
        }

        return $ret;
    }
}


