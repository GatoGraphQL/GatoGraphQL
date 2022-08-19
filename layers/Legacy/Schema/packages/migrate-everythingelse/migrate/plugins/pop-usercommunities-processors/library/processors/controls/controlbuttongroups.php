<?php

class GD_URE_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE = 'ure-controlbuttongroup-contentsource';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::COMPONENT_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY];
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::COMPONENT_URE_ANCHORCONTROL_CONTENTSOURCEUSER];
                break;
        }
        
        return $ret;
    }
}


