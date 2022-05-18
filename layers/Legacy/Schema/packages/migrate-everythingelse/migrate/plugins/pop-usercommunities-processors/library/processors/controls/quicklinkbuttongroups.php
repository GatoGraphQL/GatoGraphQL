<?php

class GD_URE_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP = 'ure-quicklinkbuttongroup-user-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_Buttons::class, GD_URE_Module_Processor_Buttons::COMPONENT_URE_BUTTON_EDITMEMBERSHIP];
                break;
        }
        
        return $ret;
    }
}


