<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Locations_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST = 'controlgroup-blockmappostlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST = 'controlgroup-blockauthormappostlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST = 'controlgroup-blocktagmappostlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKMAPUSERLIST = 'controlgroup-blockmapuserlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKMAPUSERLIST],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST:
            case self::COMPONENT_CONTROLGROUP_BLOCKMAPUSERLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST:
                $ret[] = [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
        }

        return $ret;
    }
}


