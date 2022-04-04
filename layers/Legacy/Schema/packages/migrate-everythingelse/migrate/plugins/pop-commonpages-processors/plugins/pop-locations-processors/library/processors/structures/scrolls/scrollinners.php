<?php

class PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_WHOWEARE_MAP = 'scrollinner-whoweare-map';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_MAP],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_WHOWEARE_MAP:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_SCROLLINNER_WHOWEARE_MAP => [GD_EM_Module_Processor_MultipleUserLayouts::class, GD_EM_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


