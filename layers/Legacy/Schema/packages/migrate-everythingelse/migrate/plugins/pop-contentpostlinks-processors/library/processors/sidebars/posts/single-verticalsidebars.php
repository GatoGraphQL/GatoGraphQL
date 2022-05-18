<?php

class PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_LINK = 'vertical-sidebar-single-link';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_LINK],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_LINK => [PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners::class, PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LINK],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_LINK:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



