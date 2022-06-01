<?php

class GD_URE_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR = 'multiple-section-individuals-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR = 'multiple-section-organizations-sidebar';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR => [GD_URE_Module_Processor_CustomSectionSidebarInners::class, GD_URE_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR],
            self::COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR => [GD_URE_Module_Processor_CustomSectionSidebarInners::class, GD_URE_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR],
        );
        if ($block = $blocks[$component->name] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR => POP_SCREEN_USERS,
            self::COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR => POP_SCREEN_USERS,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


