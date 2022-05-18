<?php

class Wassup_Module_Processor_CustomVerticalTagSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_TAG = 'vertical-sidebar-tag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_TAG],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_TAG => [Wassup_Module_Processor_CustomVerticalTagSidebarInners::class, Wassup_Module_Processor_CustomVerticalTagSidebarInners::COMPONENT_VERTICALSIDEBARINNER_TAG],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBAR_TAG:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



