<?php

class PoP_Module_Processor_ReferencesLayouts extends PoP_Module_Processor_ReferencesLayoutsBase
{
    public const MODULE_LAYOUT_REFERENCES_LINE = 'layout-references-line';
    public const MODULE_LAYOUT_REFERENCES_RELATED = 'layout-references-related';
    public const MODULE_LAYOUT_REFERENCES_ADDONS = 'layout-references-addons';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_REFERENCES_LINE],
            [self::class, self::MODULE_LAYOUT_REFERENCES_RELATED],
            [self::class, self::MODULE_LAYOUT_REFERENCES_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_LAYOUT_REFERENCES_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_LINE],
            self::MODULE_LAYOUT_REFERENCES_RELATED => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_RELATED],
            self::MODULE_LAYOUT_REFERENCES_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_ADDONS],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



