<?php

class PoP_ContentPostLinksCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR = 'multiple-section-mycontentpostlinks-sidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => [PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::class, PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getScreen(array $componentVariation)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($componentVariation);
    }

    public function getScreengroup(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($componentVariation);
    }
}


