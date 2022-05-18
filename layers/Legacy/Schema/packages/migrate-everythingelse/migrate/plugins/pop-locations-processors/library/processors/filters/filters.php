<?php

class PoP_Locations_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_LOCATIONS = 'filter-locations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_LOCATIONS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FILTER_LOCATIONS => [PoP_Locations_Module_Processor_CustomFilterInners::class, PoP_Locations_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_LOCATIONS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



