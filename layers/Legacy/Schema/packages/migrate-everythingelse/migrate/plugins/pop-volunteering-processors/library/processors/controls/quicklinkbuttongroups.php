<?php

class PoP_Volunteering_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER = 'quicklinkbuttongroup-postvolunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER:
                $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                break;
        }
        
        return $ret;
    }
}


