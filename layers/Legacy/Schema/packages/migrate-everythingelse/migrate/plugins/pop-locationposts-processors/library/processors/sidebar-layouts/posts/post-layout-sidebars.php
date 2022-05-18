<?php

class GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST = 'layout-postsidebar-vertical-locationpost';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST = 'layout-postsidebar-horizontal-locationpost';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST = 'layout-postsidebarcompact-horizontal-locationpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST],

        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



