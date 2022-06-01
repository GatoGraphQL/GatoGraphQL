<?php

class UserStance_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_VERTICALSIDEBAR_SINGLE_STANCE = 'vertical-sidebar-single-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_SINGLE_STANCE],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_SINGLE_STANCE => [UserStance_Module_Processor_CustomVerticalSingleSidebarInners::class, UserStance_Module_Processor_CustomVerticalSingleSidebarInners::COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBAR_SINGLE_STANCE:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



