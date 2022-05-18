<?php

class GD_URE_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE = 'ure-controlbuttongroup-contentsource';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE:
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY];
                $ret[] = [GD_URE_Module_Processor_AnchorControls::class, GD_URE_Module_Processor_AnchorControls::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER];
                break;
        }
        
        return $ret;
    }
}


