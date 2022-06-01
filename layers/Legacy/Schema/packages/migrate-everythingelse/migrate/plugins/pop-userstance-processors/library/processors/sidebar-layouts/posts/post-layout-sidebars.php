<?php

class UserStance_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE = 'layout-postsidebar-vertical-stance';
    public final const COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE = 'layout-postsidebar-horizontal-stance';
    public final const COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE = 'layout-postsidebarcompact-horizontal-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE],

        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE:
                $this->appendProp($component, $props, 'class', 'vertical stances');
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE:
            case self::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE:
                $this->appendProp($component, $props, 'class', 'horizontal stances');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



