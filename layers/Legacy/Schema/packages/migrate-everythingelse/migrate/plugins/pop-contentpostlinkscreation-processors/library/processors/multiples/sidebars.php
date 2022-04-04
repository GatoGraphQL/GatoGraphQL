<?php

class PoP_ContentPostLinksCreation_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR = 'multiple-section-mycontentpostlinks-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => [PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::class, PoP_ContentPostLinksCreation_Module_Processor_SidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYCONTENTPOSTLINKS_SIDEBAR],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR => POP_SCREEN_MYCONTENT,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($module);
    }
}


