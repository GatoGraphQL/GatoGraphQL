<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_UserCommunities_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR = 'multiple-section-mymembers-sidebar';
    public final const MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR = 'multiple-section-communities-sidebar';
    public final const MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR = 'multiple-authorcommunitymembers-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::MODULE_MULTIPLE_AUTHORSECTIONINNER_COMMUNITYMEMBERS_SIDEBAR],
                );
                if ($filter = $filters[$componentVariation[1]] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = \PoP\Root\App::applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $componentVariation
                );
                break;

            default:
                $inners = array(
                    self::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYMEMBERS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR => [PoP_UserCommunities_Module_Processor_SectionSidebarInners::class, PoP_UserCommunities_Module_Processor_SectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_COMMUNITIES_SIDEBAR],
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
            self::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR => POP_URE_SCREEN_MYUSERS,
            self::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR => POP_SCREEN_USERS,
            self::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($componentVariation);
    }

    public function getScreengroup(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($componentVariation);
    }
}


