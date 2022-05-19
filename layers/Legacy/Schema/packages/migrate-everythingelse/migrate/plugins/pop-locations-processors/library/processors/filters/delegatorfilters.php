<?php

class PoP_Locations_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_LOCATIONS = 'delegatorfilter-locations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_LOCATIONS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_LOCATIONS => [PoP_Locations_Module_Processor_CustomSimpleFilterInners::class, PoP_Locations_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LOCATIONS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



