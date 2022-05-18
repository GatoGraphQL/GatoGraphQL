<?php

class PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR = 'multiple-sectioninner-mylocationposts-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoPSPEM_Module_Processor_CustomDelegatorFilters::class, PoPSPEM_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYLOCATIONPOSTS];
                break;
        }
        
        return $ret;
    }
}



