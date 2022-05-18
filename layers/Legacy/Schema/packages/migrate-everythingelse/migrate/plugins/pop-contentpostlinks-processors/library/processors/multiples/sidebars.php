<?php

class PoP_ContentPostLinks_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR = 'multiple-section-contentpostlinks-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => [PoP_ContentPostLinks_Module_Processor_SidebarInners::class, PoP_ContentPostLinks_Module_Processor_SidebarInners::MODULE_MULTIPLE_SECTIONINNER_POSTLINKS_SIDEBAR],
        );
        if ($block = $blocks[$componentVariation[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $componentVariation)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => POP_SCREEN_SECTION,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($componentVariation);
    }

    public function getScreengroup(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($componentVariation);
    }
}


