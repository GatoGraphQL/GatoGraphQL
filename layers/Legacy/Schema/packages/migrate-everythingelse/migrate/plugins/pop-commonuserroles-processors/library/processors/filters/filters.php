<?php

class PoP_CommonUserRoles_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_INDIVIDUALS = 'filter-individuals';
    public final const COMPONENT_FILTER_ORGANIZATIONS = 'filter-organizations';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_INDIVIDUALS,
            self::COMPONENT_FILTER_ORGANIZATIONS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_INDIVIDUALS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS],
            self::COMPONENT_FILTER_ORGANIZATIONS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


