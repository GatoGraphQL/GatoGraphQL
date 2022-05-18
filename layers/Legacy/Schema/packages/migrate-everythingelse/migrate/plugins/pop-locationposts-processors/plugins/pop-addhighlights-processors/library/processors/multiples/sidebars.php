<?php

class PoP_LocationPosts_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR = 'multiple-single-locationpost-highlightssidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                return POP_SCREEN_SINGLEHIGHLIGHTS;
        }
        
        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($component);
    }
}


