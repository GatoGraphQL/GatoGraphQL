<?php

class GD_URE_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_INVITENEWMEMBERS = 'controlbuttongroup-invitenewmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_INVITENEWMEMBERS];
                break;
        }
        
        return $ret;
    }
}


