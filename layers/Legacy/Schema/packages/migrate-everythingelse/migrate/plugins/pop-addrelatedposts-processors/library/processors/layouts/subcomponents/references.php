<?php

class PoP_Module_Processor_ReferencesLayouts extends PoP_Module_Processor_ReferencesLayoutsBase
{
    public final const COMPONENT_LAYOUT_REFERENCES_LINE = 'layout-references-line';
    public final const COMPONENT_LAYOUT_REFERENCES_RELATED = 'layout-references-related';
    public final const COMPONENT_LAYOUT_REFERENCES_ADDONS = 'layout-references-addons';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_REFERENCES_LINE,
            self::COMPONENT_LAYOUT_REFERENCES_RELATED,
            self::COMPONENT_LAYOUT_REFERENCES_ADDONS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_LAYOUT_REFERENCES_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE],
            self::COMPONENT_LAYOUT_REFERENCES_RELATED => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED],
            self::COMPONENT_LAYOUT_REFERENCES_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



