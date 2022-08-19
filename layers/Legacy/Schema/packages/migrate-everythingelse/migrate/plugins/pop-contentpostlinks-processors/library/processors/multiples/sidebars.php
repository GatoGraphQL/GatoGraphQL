<?php

class PoP_ContentPostLinks_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR = 'multiple-section-contentpostlinks-sidebar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => [PoP_ContentPostLinks_Module_Processor_SidebarInners::class, PoP_ContentPostLinks_Module_Processor_SidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_POSTLINKS_SIDEBAR],
        );
        if ($block = $blocks[$component->name] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => POP_SCREEN_SECTION,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


