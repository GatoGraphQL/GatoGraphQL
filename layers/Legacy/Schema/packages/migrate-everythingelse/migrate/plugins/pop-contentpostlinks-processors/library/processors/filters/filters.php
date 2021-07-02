<?php

class PoP_ContentPostLinks_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_LINKS = 'filter-links';
    public const MODULE_FILTER_AUTHORLINKS = 'filter-authorlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_LINKS],
            [self::class, self::MODULE_FILTER_AUTHORLINKS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_LINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_LINKS],
            self::MODULE_FILTER_AUTHORLINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORLINKS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


