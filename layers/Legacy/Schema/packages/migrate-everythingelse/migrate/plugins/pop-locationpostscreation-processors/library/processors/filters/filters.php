<?php

class GD_Custom_EM_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_MYLOCATIONPOSTS = 'filter-mylocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYLOCATIONPOSTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FILTER_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_CustomFilterInners::class, GD_Custom_EM_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYLOCATIONPOSTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



