<?php

class PoP_Module_Processor_ReferencesLayouts extends PoP_Module_Processor_ReferencesLayoutsBase
{
    public final const COMPONENT_LAYOUT_REFERENCES_LINE = 'layout-references-line';
    public final const COMPONENT_LAYOUT_REFERENCES_RELATED = 'layout-references-related';
    public final const COMPONENT_LAYOUT_REFERENCES_ADDONS = 'layout-references-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_REFERENCES_LINE],
            [self::class, self::COMPONENT_LAYOUT_REFERENCES_RELATED],
            [self::class, self::COMPONENT_LAYOUT_REFERENCES_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_LAYOUT_REFERENCES_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE],
            self::COMPONENT_LAYOUT_REFERENCES_RELATED => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED],
            self::COMPONENT_LAYOUT_REFERENCES_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



