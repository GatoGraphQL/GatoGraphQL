<?php

class PoP_Events_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR = 'multiple-single-event-highlightssidebar';
    public final const MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR = 'multiple-single-pastevent-highlightssidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
                // Comment Leo 27/07/2016: can't have the filter for "POSTAUTHORSSIDEBAR", because to get the authors we do:
                // $ret['include'] = gdGetPostauthors($post_id); (in function addDataloadqueryargsSingleauthors)
                // and the include cannot be filtered. Once it's there, it's final.
                // (And also, it doesn't look so nice to add the filter for the authors, since most likely there is always only 1 author!)
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR];
                break;

            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
            self::MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }
}


