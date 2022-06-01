<?php

class PoP_Module_Processor_MaxHeightLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT = 'multicomponent-simpleview-postcontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE];
                $ret[] = [PoP_Module_Processor_MaxHeightLayouts::class, PoP_Module_Processor_MaxHeightLayouts::COMPONENT_LAYOUT_MAXHEIGHT_POSTCONTENT];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:
                // Change the "In response to" tag from 'h4' to 'em'
                $this->setProp([PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCES_LINE], $props, 'title-htmltag', 'em');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



