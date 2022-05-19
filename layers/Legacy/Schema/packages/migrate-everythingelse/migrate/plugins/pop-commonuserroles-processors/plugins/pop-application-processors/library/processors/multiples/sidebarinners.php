<?php

class GD_URE_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR = 'multiple-sectioninner-organizations-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR = 'multiple-sectioninner-individuals-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_USERS];
                $ret[] = [GD_URE_Module_Processor_CustomDelegatorFilters::class, GD_URE_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_ORGANIZATIONS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_USERS];
                $ret[] = [GD_URE_Module_Processor_CustomDelegatorFilters::class, GD_URE_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_INDIVIDUALS];
                break;
        }
        
        return $ret;
    }
}



