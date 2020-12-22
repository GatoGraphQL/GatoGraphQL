<?php

class PoP_CommonUserRoles_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_INDIVIDUALS = 'filter-individuals';
    public const MODULE_FILTER_ORGANIZATIONS = 'filter-organizations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_INDIVIDUALS],
            [self::class, self::MODULE_FILTER_ORGANIZATIONS],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_INDIVIDUALS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_INDIVIDUALS],
            self::MODULE_FILTER_ORGANIZATIONS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_ORGANIZATIONS],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}


