<?php

class GD_URE_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_MYMEMBERS = 'filter-mymembers';
    public final const COMPONENT_FILTER_COMMUNITIES = 'filter-communities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_MYMEMBERS,
            self::COMPONENT_FILTER_COMMUNITIES,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_MYMEMBERS => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYMEMBERS],
            self::COMPONENT_FILTER_COMMUNITIES => [GD_URE_Module_Processor_CustomFilterInners::class, GD_URE_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_COMMUNITIES],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


