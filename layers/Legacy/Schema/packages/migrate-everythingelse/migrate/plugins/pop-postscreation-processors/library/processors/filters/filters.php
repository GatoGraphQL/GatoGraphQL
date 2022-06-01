<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_MYLINKS = 'filter-mylinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_MYLINKS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYLINKS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


