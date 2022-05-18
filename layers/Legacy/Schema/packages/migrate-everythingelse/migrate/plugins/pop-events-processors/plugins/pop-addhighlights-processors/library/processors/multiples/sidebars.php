<?php

class PoP_Events_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR = 'multiple-single-event-highlightssidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR = 'multiple-single-pastevent-highlightssidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
                // Comment Leo 27/07/2016: can't have the filter for "POSTAUTHORSSIDEBAR", because to get the authors we do:
                // $ret['include'] = gdGetPostauthors($post_id); (in function addDataloadqueryargsSingleauthors)
                // and the include cannot be filtered. Once it's there, it's final.
                // (And also, it doesn't look so nice to add the filter for the authors, since most likely there is always only 1 author!)
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR];
                break;

            case self::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
            self::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


