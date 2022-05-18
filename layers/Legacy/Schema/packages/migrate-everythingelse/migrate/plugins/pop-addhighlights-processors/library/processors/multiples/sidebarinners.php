<?php

class PoP_AddHighlights_Module_Processor_SidebarMultipleInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR = 'multiple-sectioninner-highlights-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_MYHIGHLIGHTS_SIDEBAR = 'multiple-sectioninner-myhighlights-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTIONINNER_MYHIGHLIGHTS_SIDEBAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR:
                $ret[] = [PoP_AddHighlights_Module_Processor_ButtonGroups::class, PoP_AddHighlights_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_HIGHLIGHTS];
                $ret[] = [PoP_AddHighlights_Module_Processor_CustomDelegatorFilters::class, PoP_AddHighlights_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_HIGHLIGHTS];
                break;

            case self::MODULE_MULTIPLE_SECTIONINNER_MYHIGHLIGHTS_SIDEBAR:
                $ret[] = [PoP_AddHighlights_Module_Processor_ButtonGroups::class, PoP_AddHighlights_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_MYHIGHLIGHTS];
                $ret[] = [PoP_AddHighlights_Module_Processor_CustomDelegatorFilters::class, PoP_AddHighlights_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_MYHIGHLIGHTS];
                break;
        }
        
        return $ret;
    }
}



