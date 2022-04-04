<?php

class GD_URE_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR = 'multiple-section-individuals-sidebar';
    public final const MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR = 'multiple-section-organizations-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR => [GD_URE_Module_Processor_CustomSectionSidebarInners::class, GD_URE_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_INDIVIDUALS_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR => [GD_URE_Module_Processor_CustomSectionSidebarInners::class, GD_URE_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_ORGANIZATIONS_SIDEBAR],
        );
        if ($block = $blocks[$module[1]] ?? null) {
            $ret[] = $block;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR => POP_SCREEN_USERS,
            self::MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR => POP_SCREEN_USERS,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }
}


