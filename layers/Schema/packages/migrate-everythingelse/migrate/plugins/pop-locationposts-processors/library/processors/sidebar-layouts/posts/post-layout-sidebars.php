<?php

class GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST = 'layout-postsidebar-vertical-locationpost';
    public const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST = 'layout-postsidebar-horizontal-locationpost';
    public const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST = 'layout-postsidebarcompact-horizontal-locationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST],

        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST:
                $this->appendProp($module, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST:
                $this->appendProp($module, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



