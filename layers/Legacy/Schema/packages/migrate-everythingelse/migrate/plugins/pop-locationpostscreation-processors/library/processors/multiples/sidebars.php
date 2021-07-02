<?php

class PoPSPEM_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR = 'multiple-section-mylocationposts-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR => [PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners::class, PoP_LocationPostsCreation_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYLOCATIONPOSTS_SIDEBAR],
        );
        if ($block = $blocks[$module[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_MYCONTENT;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($module);
    }
}


