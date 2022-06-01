<?php

class GD_EM_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT = 'vertical-sidebar-single-event';
    public final const COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT = 'vertical-sidebar-single-pastevent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT],
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_EVENT],
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_PASTEVENT],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT:
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



