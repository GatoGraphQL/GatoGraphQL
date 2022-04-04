<?php

class PoP_LocationPosts_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR = 'multiple-single-locationpost-highlightssidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                return POP_SCREEN_SINGLEHIGHLIGHTS;
        }
        
        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($module);
    }
}


