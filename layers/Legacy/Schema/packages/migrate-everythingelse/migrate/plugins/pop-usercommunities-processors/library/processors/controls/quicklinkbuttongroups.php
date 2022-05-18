<?php

class GD_URE_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP = 'ure-quicklinkbuttongroup-user-editmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_Buttons::class, GD_URE_Module_Processor_Buttons::MODULE_URE_BUTTON_EDITMEMBERSHIP];
                break;
        }
        
        return $ret;
    }
}


