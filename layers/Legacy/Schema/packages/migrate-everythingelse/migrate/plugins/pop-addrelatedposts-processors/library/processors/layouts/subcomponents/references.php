<?php

class PoP_Module_Processor_ReferencesLayouts extends PoP_Module_Processor_ReferencesLayoutsBase
{
    public final const MODULE_LAYOUT_REFERENCES_LINE = 'layout-references-line';
    public final const MODULE_LAYOUT_REFERENCES_RELATED = 'layout-references-related';
    public final const MODULE_LAYOUT_REFERENCES_ADDONS = 'layout-references-addons';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_REFERENCES_LINE],
            [self::class, self::MODULE_LAYOUT_REFERENCES_RELATED],
            [self::class, self::MODULE_LAYOUT_REFERENCES_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_LAYOUT_REFERENCES_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_LINE],
            self::MODULE_LAYOUT_REFERENCES_RELATED => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_RELATED],
            self::MODULE_LAYOUT_REFERENCES_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_ADDONS],
        );
        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



