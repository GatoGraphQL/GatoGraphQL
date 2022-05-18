<?php

class PoP_Volunteering_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER = 'quicklinkbuttongroup-postvolunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER:
                $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                break;
        }
        
        return $ret;
    }
}


