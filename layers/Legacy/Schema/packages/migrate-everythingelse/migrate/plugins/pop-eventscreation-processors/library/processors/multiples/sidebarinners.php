<?php

class PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR = 'multiple-sectioninner-myevents-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR = 'multiple-sectioninner-mypastevents-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
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



