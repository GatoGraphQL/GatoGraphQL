<?php

class GD_URE_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS = 'controlbuttongroup-invitenewmembers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_INVITENEWMEMBERS];
                break;
        }
        
        return $ret;
    }
}


