<?php

class PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR = 'multiple-sectioninner-myevents-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR = 'multiple-sectioninner-mypastevents-sidebar';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::class, PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYEVENTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::class, PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYEVENTS];
                break;
        }
        
        return $ret;
    }
}



