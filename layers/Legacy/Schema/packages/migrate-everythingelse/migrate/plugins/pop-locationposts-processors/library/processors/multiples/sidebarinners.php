<?php

class GD_Custom_EM_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTIONINNER_LOCATIONPOSTS_SIDEBAR = 'multiple-sectioninner-locationposts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_TAGLOCATIONPOSTS_SIDEBAR = 'multiple-sectioninner-taglocationposts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_AUTHORLOCATIONPOSTS_SIDEBAR = 'multiple-sectioninner-authorlocationposts-sidebar';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SECTIONINNER_LOCATIONPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTIONINNER_TAGLOCATIONPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORLOCATIONPOSTS_SIDEBAR,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SECTIONINNER_LOCATIONPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_SECTIONWITHMAP];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::class, PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_LOCATIONPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_TAGLOCATIONPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSECTIONWITHMAP];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::class, PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGLOCATIONPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORLOCATIONPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSECTIONWITHMAP];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::class, PoP_LocationPosts_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORLOCATIONPOSTS];
                break;
        }
        
        return $ret;
    }
}



