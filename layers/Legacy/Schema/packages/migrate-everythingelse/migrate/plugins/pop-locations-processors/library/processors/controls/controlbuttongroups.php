<?php

class PoP_Locations_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP = 'controlbuttongroup-togglemap';
    public final const COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP = 'controlbuttongroup-toggleauthormap';
    public final const COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP = 'controlbuttongroup-toggletagmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP,
            self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP,
            self::COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
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


