<?php

class GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST = 'layout-postsidebar-vertical-locationpost';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST = 'layout-postsidebar-horizontal-locationpost';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST = 'layout-postsidebarcompact-horizontal-locationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST],

        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LOCATIONPOST:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



