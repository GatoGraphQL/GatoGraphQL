<?php

class PoP_ContentPostLinks_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_CONTENTPOSTLINKS = 'delegatorfilter-contentpostlinks';
    public const MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS = 'delegatorfilter-authorcontentpostlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_CONTENTPOSTLINKS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_LINKS],
            self::MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORLINKS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



