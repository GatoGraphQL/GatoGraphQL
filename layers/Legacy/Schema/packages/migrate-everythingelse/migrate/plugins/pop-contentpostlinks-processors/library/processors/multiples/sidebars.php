<?php

class PoP_ContentPostLinks_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR = 'multiple-section-contentpostlinks-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => [PoP_ContentPostLinks_Module_Processor_SidebarInners::class, PoP_ContentPostLinks_Module_Processor_SidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_POSTLINKS_SIDEBAR],
        );
        if ($block = $blocks[$component[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => POP_SCREEN_SECTION,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_POSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


