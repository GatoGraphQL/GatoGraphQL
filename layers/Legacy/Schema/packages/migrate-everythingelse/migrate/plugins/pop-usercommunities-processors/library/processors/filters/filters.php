<?php

class GD_URE_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_MYMEMBERS = 'filter-mymembers';
    public const MODULE_FILTER_COMMUNITIES = 'filter-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYMEMBERS],
            [self::class, self::MODULE_FILTER_COMMUNITIES],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_MYMEMBERS => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYMEMBERS],
            self::MODULE_FILTER_COMMUNITIES => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_COMMUNITIES],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


