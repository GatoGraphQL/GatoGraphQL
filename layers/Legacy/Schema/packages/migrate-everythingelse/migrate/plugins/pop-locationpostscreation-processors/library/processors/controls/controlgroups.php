<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class CommonPages_EM_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST = 'controlgroup-mylocationpostlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST:
                $addposts = array(
                    self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST => [CommonPages_EM_Module_Processor_ControlButtonGroups::class, CommonPages_EM_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST],
                );
                $ret[] = $addposts[$component[1]];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


