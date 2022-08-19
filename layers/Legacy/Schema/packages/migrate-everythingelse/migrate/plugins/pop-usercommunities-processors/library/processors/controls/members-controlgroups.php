<?php

class GD_URE_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_MYMEMBERS = 'controlgroup-mymembers';
    public final const COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS = 'controlgroup-myblockmembers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLGROUP_MYMEMBERS,
            self::COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTROLGROUP_MYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


