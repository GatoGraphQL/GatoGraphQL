<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_URE_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE = 'ure-controlgroup-contentsource';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE];
                break;
        }

        return $ret;
    }
}


