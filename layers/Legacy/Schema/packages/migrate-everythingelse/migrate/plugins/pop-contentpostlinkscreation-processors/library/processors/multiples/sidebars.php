<?php

class PoP_ContentPostLinksCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR = 'multiple-section-mycontentpostlinks-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => [PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::class, PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($component);
    }
}


