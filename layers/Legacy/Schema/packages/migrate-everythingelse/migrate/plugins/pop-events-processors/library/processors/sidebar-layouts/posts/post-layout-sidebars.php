<?php

class GD_EM_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT = 'layout-postsidebar-vertical-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT = 'layout-postsidebar-vertical-pastevent';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT = 'layout-postsidebar-horizontal-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT = 'layout-postsidebar-horizontal-pastevent';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT = 'layout-postsidebarcompact-horizontal-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT = 'layout-postsidebarcompact-horizontal-pastevent';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT,
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT,
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT,
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT,

        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



