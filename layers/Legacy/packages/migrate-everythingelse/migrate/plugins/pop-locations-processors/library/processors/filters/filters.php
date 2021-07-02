<?php

class PoP_Locations_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_LOCATIONS = 'filter-locations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_LOCATIONS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_LOCATIONS => [PoP_Locations_Module_Processor_CustomFilterInners::class, PoP_Locations_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_LOCATIONS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



