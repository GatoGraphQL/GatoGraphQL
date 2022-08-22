<?php

class PoP_EventsCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR = 'multiple-section-myevents-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR = 'multiple-section-mypastevents-sidebar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYEVENTS_SIDEBAR],
            self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => [PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_EventsCreation_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYPASTEVENTS_SIDEBAR],
        );
        if ($block = $blocks[$component->name] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SECTION_MYEVENTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


