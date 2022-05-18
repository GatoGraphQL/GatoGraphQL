<?php

class GD_EM_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_EVENT = 'vertical-sidebar-single-event';
    public final const MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT = 'vertical-sidebar-single-pastevent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT],
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_EVENT],
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_PASTEVENT],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT:
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



