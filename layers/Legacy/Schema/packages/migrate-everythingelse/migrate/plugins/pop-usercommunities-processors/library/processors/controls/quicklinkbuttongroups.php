<?php

class GD_URE_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP = 'ure-quicklinkbuttongroup-user-editmembership';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_Buttons::class, GD_URE_Module_Processor_Buttons::COMPONENT_URE_BUTTON_EDITMEMBERSHIP];
                break;
        }
        
        return $ret;
    }
}


