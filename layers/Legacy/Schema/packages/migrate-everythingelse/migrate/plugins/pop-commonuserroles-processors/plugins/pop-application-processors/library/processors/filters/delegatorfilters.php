<?php

class GD_URE_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_INDIVIDUALS = 'delegatorfilter-individuals';
    public final const MODULE_DELEGATORFILTER_ORGANIZATIONS = 'delegatorfilter-organizations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_INDIVIDUALS],
            [self::class, self::COMPONENT_DELEGATORFILTER_ORGANIZATIONS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_INDIVIDUALS => [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS],
            self::COMPONENT_DELEGATORFILTER_ORGANIZATIONS => [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



