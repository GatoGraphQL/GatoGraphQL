<?php

class PoP_ContentPostLinks_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR = 'multiple-section-contentpostlinks-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => [PoP_ContentPostLinks_Module_Processor_SidebarInners::class, PoP_ContentPostLinks_Module_Processor_SidebarInners::MODULE_MULTIPLE_SECTIONINNER_POSTLINKS_SIDEBAR],
        );
        if ($block = $blocks[$module[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR => POP_SCREEN_SECTION,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_POSTLINKS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }
}


