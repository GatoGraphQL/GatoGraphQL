<?php

class PoP_Volunteering_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER = 'quicklinkbuttongroup-postvolunteer';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER:
                $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                break;
        }
        
        return $ret;
    }
}


