<?php

class PoP_UserCommunities_Module_Processor_SectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SECTIONINNER_MYMEMBERS_SIDEBAR = 'multiple-sectioninner-mymembers-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_COMMUNITIES_SIDEBAR = 'multiple-sectioninner-communities-sidebar';
    public final const MODULE_MULTIPLE_AUTHORSECTIONINNER_COMMUNITYMEMBERS_SIDEBAR = 'multiple-authorsectioninner-communitymembers-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYMEMBERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_COMMUNITIES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORSECTIONINNER_COMMUNITYMEMBERS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_MYMEMBERS_SIDEBAR:
                $ret[] = [PoP_UserCommunities_ModuleProcessor_ButtonGroups::class, PoP_UserCommunities_ModuleProcessor_ButtonGroups::MODULE_BUTTONGROUP_MYUSERS];
                $ret[] = [PoP_UserCommunities_Module_Processor_CustomDelegatorFilters::class, PoP_UserCommunities_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYMEMBERS];
                break;
                    
            case self::MODULE_MULTIPLE_SECTIONINNER_COMMUNITIES_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_USERS];
                $ret[] = [PoP_UserCommunities_Module_Processor_CustomDelegatorFilters::class, PoP_UserCommunities_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_COMMUNITIES];
                break;

            case self::MODULE_MULTIPLE_AUTHORSECTIONINNER_COMMUNITYMEMBERS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_AUTHORUSERS];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS];
                break;
        }
        
        return $ret;
    }
}



