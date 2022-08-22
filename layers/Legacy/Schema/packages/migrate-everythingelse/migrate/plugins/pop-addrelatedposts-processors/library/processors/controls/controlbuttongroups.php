<?php

class PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST = 'controlbuttongroup-addrelatedpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST:
                $ret[] = [PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::class, PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST];
                break;
        }
        
        return $ret;
    }
}


