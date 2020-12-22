<?php

class PoP_ContentPostLinksCreation_Module_Processor_SidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR = 'multiple-sectioninner-mycontentpostlinks-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_ContentPostLinksCreation_Module_Processor_CustomDelegatorFilters::class, PoP_ContentPostLinksCreation_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS];
                break;
        }
        
        return $ret;
    }
}


