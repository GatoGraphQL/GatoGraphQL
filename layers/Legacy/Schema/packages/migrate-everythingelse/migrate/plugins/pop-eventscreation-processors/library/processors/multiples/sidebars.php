<?php

class PoP_EventsCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR = 'multiple-section-myevents-sidebar';
    public final const MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR = 'multiple-section-mypastevents-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR],
        );
        if ($block = $blocks[$componentVariation[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $componentVariation)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($componentVariation);
    }

    public function getScreengroup(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($componentVariation);
    }
}


