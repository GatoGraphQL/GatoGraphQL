<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class CommonPages_EM_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST = 'controlgroup-mylocationpostlist';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST:
                $addposts = array(
                    self::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST => [CommonPages_EM_Module_Processor_ControlButtonGroups::class, CommonPages_EM_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST],
                );
                $ret[] = $addposts[$component->name];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


