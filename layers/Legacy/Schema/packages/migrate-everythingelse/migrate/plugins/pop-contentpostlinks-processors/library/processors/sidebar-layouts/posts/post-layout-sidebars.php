<?php

class PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK = 'layout-postsidebar-vertical-link';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK = 'layout-postsidebar-horizontal-link';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK = 'layout-postsidebarcompact-horizontal-link';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK => [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



