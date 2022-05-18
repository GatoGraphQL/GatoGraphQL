<?php

class GD_URE_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE = 'ure-controlbuttongroup-contentsource';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::COMPONENT_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY];
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::COMPONENT_URE_ANCHORCONTROL_CONTENTSOURCEUSER];
                break;
        }
        
        return $ret;
    }
}


