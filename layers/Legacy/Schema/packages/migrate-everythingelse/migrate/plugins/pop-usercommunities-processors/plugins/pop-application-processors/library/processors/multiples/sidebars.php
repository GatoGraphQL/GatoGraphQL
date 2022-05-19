<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_UserCommunities_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR = 'multiple-section-mymembers-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR = 'multiple-section-communities-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR = 'multiple-authorcommunitymembers-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_COMMUNITYMEMBERS_SIDEBAR],
                );
                if ($filter = $filters[$component[1]] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = \PoP\Root\App::applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $component
                );
                break;

            default:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYMEMBERS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_COMMUNITIES_SIDEBAR],
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
            self::COMPONENT_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR => POP_URE_SCREEN_MYUSERS,
            self::COMPONENT_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR => POP_SCREEN_USERS,
            self::COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::COMPONENT_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($component);
    }
}


