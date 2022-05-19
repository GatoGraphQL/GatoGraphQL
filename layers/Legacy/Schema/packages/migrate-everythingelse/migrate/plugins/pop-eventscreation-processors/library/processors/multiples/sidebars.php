<?php

class PoP_EventsCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR = 'multiple-section-myevents-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR = 'multiple-section-mypastevents-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR],
            self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR],
        );
        if ($block = $blocks[$component[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


