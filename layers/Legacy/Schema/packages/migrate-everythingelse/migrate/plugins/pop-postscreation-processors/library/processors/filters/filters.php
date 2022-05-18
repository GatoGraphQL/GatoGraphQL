<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_MYLINKS = 'filter-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_MYLINKS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYLINKS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


