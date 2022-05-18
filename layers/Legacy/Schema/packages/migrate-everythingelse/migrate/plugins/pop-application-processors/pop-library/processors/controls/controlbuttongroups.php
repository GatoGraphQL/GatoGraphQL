<?php

class PoP_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_ADDPOST = 'controlbuttongroup-addpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ADDPOST],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_ADDPOST:
                $ret[] = [PoP_Module_Processor_CustomAnchorControls::class, PoP_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_ADDPOST];
                if (defined('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls::class, PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_ADDPOSTLINK];
                }
                break;
        }
        
        return $ret;
    }
}


