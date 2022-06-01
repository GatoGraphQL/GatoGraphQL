<?php

class PoPSPEM_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR = 'multiple-section-mylocationposts-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR],
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $blocks = array(
            self::COMPONENT_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR => [PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR],
        );
        if ($block = $blocks[$component[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_MYCONTENT;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($component);
    }
}


