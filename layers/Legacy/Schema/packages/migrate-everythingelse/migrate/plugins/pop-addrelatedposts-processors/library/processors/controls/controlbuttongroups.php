<?php

class PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST = 'controlbuttongroup-addrelatedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST:
                $ret[] = [PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::class, PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST];
                break;
        }
        
        return $ret;
    }
}


