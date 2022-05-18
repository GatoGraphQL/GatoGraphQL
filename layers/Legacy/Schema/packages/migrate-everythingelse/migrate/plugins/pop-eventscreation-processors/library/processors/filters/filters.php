<?php

class PoP_EventsCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_MYEVENTS = 'filter-myevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_MYEVENTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_MYEVENTS => [PoP_EventsCreation_Module_Processor_CustomFilterInners::class, PoP_EventsCreation_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYEVENTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



