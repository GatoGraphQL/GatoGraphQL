<?php

class PoP_CommonUserRoles_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_INDIVIDUALS = 'filter-individuals';
    public final const COMPONENT_FILTER_ORGANIZATIONS = 'filter-organizations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_INDIVIDUALS],
            [self::class, self::COMPONENT_FILTER_ORGANIZATIONS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_INDIVIDUALS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS],
            self::COMPONENT_FILTER_ORGANIZATIONS => [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


