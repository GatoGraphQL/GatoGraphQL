<?php

class PoP_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL = 'layout-postsidebar-vertical';
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT = 'layout-postsidebar-vertical-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_POST = 'layout-postsidebar-vertical-post';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL = 'layout-postsidebar-horizontal';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT = 'layout-postsidebar-horizontal-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST = 'layout-postsidebar-horizontal-post';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL = 'layout-postsidebarcompact-horizontal';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT = 'layout-postsidebarcompact-horizontal-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST = 'layout-postsidebarcompact-horizontal-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_POST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],

        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL],
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT],
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL:
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_POST:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



