<?php

class PoP_AddHighlights_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR = 'multiple-section-highlights-sidebar';
    public final const MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR = 'multiple-section-myhighlights-sidebar';
    public final const MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR = 'multiple-single-highlight-sidebar';
    public final const MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR = 'multiple-single-post-highlightssidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR],
                );
                $ret[] = $filters[$componentVariation[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_POST_SIDEBAR];
                break;

            default:
                $inners = array(
                    self::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_HIGHLIGHTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR => [PoP_AddHighlights_Module_Processor_SidebarMultipleInners::class, PoP_AddHighlights_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_MYHIGHLIGHTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => [PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::class, PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR],
                );
                if ($inner = $inners[$componentVariation[1]] ?? null) {
                    $ret[] = $inner;
                }
                break;
        }

        return $ret;
    }

    public function getScreen(array $componentVariation)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR => POP_SCREEN_HIGHLIGHTS,
            self::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR => POP_SCREEN_MYHIGHLIGHTS,
            self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => POP_SCREEN_SINGLE,
            self::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($componentVariation);
    }

    public function getScreengroup(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($componentVariation);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR:
                $inners = array(
                    self::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR => [PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::class, PoP_AddHighlights_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR],
                );
                $subComponentVariation = $inners[$componentVariation[1]];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

                // Make the block be collapsible, open it when the main feed is reached, with waypoints
                $this->appendProp(array($subComponentVariation), $props, 'class', 'collapse');
                $this->mergeProp(
                    array($subComponentVariation),
                    $props,
                    'params',
                    array(
                        'data-collapse-target' => $mainblock_taget
                    )
                );
                $this->mergeJsmethodsProp(array($subComponentVariation), $props, array('waypointsToggleCollapse'));
                break;
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
}


