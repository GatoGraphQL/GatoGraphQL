<?php

class PoP_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_ADDPOST = 'controlbuttongroup-addpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ADDPOST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDPOST:
                $ret[] = [PoP_Module_Processor_CustomAnchorControls::class, PoP_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_ADDPOST];
                if (defined('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls::class, PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_ADDPOSTLINK];
                }
                break;
        }
        
        return $ret;
    }
}


