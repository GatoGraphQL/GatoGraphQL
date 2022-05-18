<?php

class GD_EM_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT = 'layout-postsidebar-vertical-event';
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT = 'layout-postsidebar-vertical-pastevent';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT = 'layout-postsidebar-horizontal-event';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT = 'layout-postsidebar-horizontal-pastevent';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT = 'layout-postsidebarcompact-horizontal-event';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT = 'layout-postsidebarcompact-horizontal-pastevent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT],

        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT],
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebarInners::class, GD_EM_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



