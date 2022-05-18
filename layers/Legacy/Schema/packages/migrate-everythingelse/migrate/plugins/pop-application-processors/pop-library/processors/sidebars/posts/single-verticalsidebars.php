<?php

class Wassup_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT = 'vertical-sidebar-single-highlight';
    public final const MODULE_VERTICALSIDEBAR_SINGLE_POST = 'vertical-sidebar-single-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT],
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_POST],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT => [Wassup_Module_Processor_CustomVerticalSingleSidebarInners::class, Wassup_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT],
            self::MODULE_VERTICALSIDEBAR_SINGLE_POST => [Wassup_Module_Processor_CustomVerticalSingleSidebarInners::class, Wassup_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_POST],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT:
            case self::MODULE_VERTICALSIDEBAR_SINGLE_POST:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



