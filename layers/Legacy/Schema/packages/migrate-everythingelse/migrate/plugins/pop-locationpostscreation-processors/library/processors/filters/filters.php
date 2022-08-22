<?php

class GD_Custom_EM_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_MYLOCATIONPOSTS = 'filter-mylocationposts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_MYLOCATIONPOSTS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_CustomFilterInners::class, GD_Custom_EM_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYLOCATIONPOSTS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



