<?php

class PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR = 'multiple-sectioninner-mylocationposts-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoPSPEM_Module_Processor_CustomDelegatorFilters::class, PoPSPEM_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYLOCATIONPOSTS];
                break;
        }
        
        return $ret;
    }
}



