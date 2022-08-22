<?php

class PoP_Module_Processor_CustomTagLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_TAGSIDEBAR_VERTICAL = 'layout-tagsidebar-vertical';
    public final const COMPONENT_LAYOUT_TAGSIDEBAR_HORIZONTAL = 'layout-tagsidebar-horizontal';
    public final const COMPONENT_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL = 'layout-tagsidebar-compacthorizontal';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_TAGSIDEBAR_VERTICAL,
            self::COMPONENT_LAYOUT_TAGSIDEBAR_HORIZONTAL,
            self::COMPONENT_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_TAGSIDEBAR_VERTICAL => [PoP_Module_Processor_CustomTagLayoutSidebarInners::class, PoP_Module_Processor_CustomTagLayoutSidebarInners::COMPONENT_LAYOUT_TAGSIDEBARINNER_VERTICAL],
            self::COMPONENT_LAYOUT_TAGSIDEBAR_HORIZONTAL => [PoP_Module_Processor_CustomTagLayoutSidebarInners::class, PoP_Module_Processor_CustomTagLayoutSidebarInners::COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL],
            self::COMPONENT_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL => [PoP_Module_Processor_CustomTagLayoutSidebarInners::class, PoP_Module_Processor_CustomTagLayoutSidebarInners::COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_TAGSIDEBAR_VERTICAL:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;

            case self::COMPONENT_LAYOUT_TAGSIDEBAR_HORIZONTAL:
            case self::COMPONENT_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



