<?php

class GD_URE_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR = 'multiple-sectioninner-organizations-sidebar';
    public const MODULE_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR = 'multiple-sectioninner-individuals-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_USERS];
                $ret[] = [GD_URE_Module_Processor_CustomDelegatorFilters::class, GD_URE_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_ORGANIZATIONS];
                break;

            case self::MODULE_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_USERS];
                $ret[] = [GD_URE_Module_Processor_CustomDelegatorFilters::class, GD_URE_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_INDIVIDUALS];
                break;
        }
        
        return $ret;
    }
}



