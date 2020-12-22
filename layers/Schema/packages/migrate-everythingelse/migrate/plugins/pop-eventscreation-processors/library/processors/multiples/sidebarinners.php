<?php

class PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR = 'multiple-sectioninner-myevents-sidebar';
    public const MODULE_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR = 'multiple-sectioninner-mypastevents-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::class, PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYEVENTS];
                break;

            case self::MODULE_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::class, PoP_EventsCreation_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYEVENTS];
                break;
        }
        
        return $ret;
    }
}



