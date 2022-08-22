<?php

class PoP_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDPOST = 'controlbuttongroup-addpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_ADDPOST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
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


