<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_URE_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_URE_CONTROLGROUP_CONTENTSOURCE = 'ure-controlgroup-contentsource';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE];
                break;
        }

        return $ret;
    }
}


