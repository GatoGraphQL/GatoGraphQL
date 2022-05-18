<?php

class GD_URE_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_MYMEMBERS = 'filter-mymembers';
    public final const MODULE_FILTER_COMMUNITIES = 'filter-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYMEMBERS],
            [self::class, self::MODULE_FILTER_COMMUNITIES],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FILTER_MYMEMBERS => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYMEMBERS],
            self::MODULE_FILTER_COMMUNITIES => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_COMMUNITIES],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


