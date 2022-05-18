<?php

class PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LINK = 'layout-postsidebar-vertical-link';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK = 'layout-postsidebar-horizontal-link';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK = 'layout-postsidebarcompact-horizontal-link';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LINK],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_LINK:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



