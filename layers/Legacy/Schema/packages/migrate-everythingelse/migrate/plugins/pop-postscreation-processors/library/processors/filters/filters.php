<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_MYLINKS = 'filter-mylinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYLINKS],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_FILTER_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYLINKS],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}


