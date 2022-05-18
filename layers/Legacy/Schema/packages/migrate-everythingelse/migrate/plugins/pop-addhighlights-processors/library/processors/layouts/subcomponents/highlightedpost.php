<?php

class PoP_Module_Processor_HighlightedPostSubcomponentLayouts extends PoP_Module_Processor_HighlightedPostSubcomponentLayoutsBase
{
    public final const COMPONENT_LAYOUT_HIGHLIGHTEDPOST_LINE = 'layout-highlightedpost-line';
    public final const COMPONENT_LAYOUT_HIGHLIGHTEDPOST_ADDONS = 'layout-highlightedpost-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_LINE],
            [self::class, self::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE],
            self::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



