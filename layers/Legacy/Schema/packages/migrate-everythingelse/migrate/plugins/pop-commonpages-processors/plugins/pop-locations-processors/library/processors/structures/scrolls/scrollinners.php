<?php

class PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_WHOWEARE_MAP = 'scrollinner-whoweare-map';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_MAP],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLLINNER_WHOWEARE_MAP:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_SCROLLINNER_WHOWEARE_MAP => [GD_EM_Module_Processor_MultipleUserLayouts::class, GD_EM_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


