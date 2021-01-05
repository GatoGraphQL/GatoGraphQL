<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS = 'delegatorfilter-mypostlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYLINKS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



