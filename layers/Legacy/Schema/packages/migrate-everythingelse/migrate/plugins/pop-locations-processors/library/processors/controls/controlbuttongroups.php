<?php

class PoP_Locations_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLEMAP = 'controlbuttongroup-togglemap';
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP = 'controlbuttongroup-toggleauthormap';
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP = 'controlbuttongroup-toggletagmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEMAP];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEAUTHORMAP];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP:
                $ret[] = [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLETAGMAP];
                break;
        }
        
        return $ret;
    }
}


