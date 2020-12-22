<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_MYLINKS = 'filter-mylinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYLINKS],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYLINKS],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}


