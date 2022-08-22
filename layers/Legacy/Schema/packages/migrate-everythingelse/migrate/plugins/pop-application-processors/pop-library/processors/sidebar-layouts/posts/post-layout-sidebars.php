<?php

class PoP_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL = 'layout-postsidebar-vertical';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT = 'layout-postsidebar-vertical-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_POST = 'layout-postsidebar-vertical-post';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL = 'layout-postsidebar-horizontal';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT = 'layout-postsidebar-horizontal-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST = 'layout-postsidebar-horizontal-post';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL = 'layout-postsidebarcompact-horizontal';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT = 'layout-postsidebarcompact-horizontal-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST = 'layout-postsidebarcompact-horizontal-post';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_POST,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST,
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL,
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST,

        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_POST:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



