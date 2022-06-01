<?php

class CommonPages_EM_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST = 'customcontrolbuttongroup-addlocationpost';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST:
                $ret[] = [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST];
                $ret = \PoP\Root\App::applyFilters(
                    'CommonPages_EM_Module_Processor_ControlButtonGroups:modules',
                    $ret,
                    $component,
                    $props
                );
                break;
        }
        
        return $ret;
    }
}


