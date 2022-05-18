<?php

class PoP_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR = 'multiple-section-highlights-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR = 'multiple-section-myhighlights-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR = 'multiple-single-highlight-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR = 'multiple-single-post-highlightssidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_POST_SIDEBAR];
                break;

            default:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_MYHIGHLIGHTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => [PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::class, PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR],
                );
                if ($inner = $inners[$component[1]] ?? null) {
                    $ret[] = $inner;
                }
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR => POP_SCREEN_HIGHLIGHTS,
            self::COMPONENT_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR => POP_SCREEN_MYHIGHLIGHTS,
            self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => POP_SCREEN_SINGLE,
            self::COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::COMPONENT_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($component);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => [PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::class, PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR],
                );
                $subComponent = $inners[$component[1]];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

                // Make the block be collapsible, open it when the main feed is reached, with waypoints
                $this->appendProp(array($subComponent), $props, 'class', 'collapse');
                $this->mergeProp(
                    array($subComponent),
                    $props,
                    'params',
                    array(
                        'data-collapse-target' => $mainblock_taget
                    )
                );
                $this->mergeJsmethodsProp(array($subComponent), $props, array('waypointsToggleCollapse'));
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}


